<?php

import_lib('core/session');
import_lib('core/TableSetup');
import_lib('utils/MultiFormat');
import_lib('interactive/Table');
import_lib('interactive/Form');
import_lib('interactive/Pager');

class Forum extends MultiFormat {
    private $forum;
    private $group;
    private $table;
    private $form;
    private $pager;

    function __construct() {
        parent::__construct('forum');
    }

    function setup() {
        $setup = new TableSetup(prefix('forum_group'));
        $setup->add('id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $setup->add('name', 'VARCHAR(64)');
        $setup->setup();

        $setup = new TableSetup(prefix('forum'));
        $setup->add('id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $setup->add('group', 'INT NOT NULL');
        $setup->add('name', 'VARCHAR(64)');
        $setup->setup();

        $setup = new TableSetup(prefix('topic'));
        $setup->add('id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $setup->add('forum', 'INT NOT NULL');
        $setup->add('user', 'INT');
        $setup->add('title', 'VARCHAR(128)');
        $setup->add('created', 'DATETIME');
        $setup->add('last_user', 'INT');
        $setup->add('updated', 'DATETIME');
        $setup->setup();

        $setup = new TableSetup(prefix('post'));
        $setup->add('id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $setup->add('topic', 'INT NOT NULL');
        $setup->add('user', 'INT');
        $setup->add('content', 'TEXT');
        $setup->add('created', 'DATETIME');
        $setup->add('edited', 'DATETIME');
        $setup->setup();
    }

    function show($type = 'default') {
        global $s;
        $s['suburl'] = array('forum', 'forum_action', 'topic', 'page');
        $s['css'][] = 'forum';
        $s['head'] = 'Forum';
        if (has_value('forum')) {
            $forum = find_value('forum');
            if (is_numeric($forum)) {
                if (!has_value('forum_action') || is_numeric(find_value('forum_action'))) {
                    $s['suburl'][1] = 'page';
                    $result = query("SELECT F.`name`, G.`name` AS `group` FROM `" . prefix('forum') . "` F LEFT JOIN `" . prefix('forum_group') . "` G ON G.`id`=F.`group` WHERE F.`id`=$forum");
                    if ($row = $result->fetch_array())
                        $s['head'] = secure($row['group'] . ' > ' . $row['name'], 'html') . ' forum';
                    $table = new Table();
                    $table->addColumn(array('title' => 'Topic', 'size' => 'huge'));
                    $table->addColumn(array('title' => 'Created', 'size' => 'large'));
                    $table->addColumn(array('title' => 'Last post', 'size' => 'large'));
                    $pager = $table->setPager();

                    $pager->query("`id`, `title`, `user`, UNIX_TIMESTAMP(`created`) AS `created`, UNIX_TIMESTAMP(`updated`) AS `updated`, `last_user`", "`" . prefix('topic') . "` WHERE `forum`=$forum ORDER BY `updated` DESC", function($row, $table) {
                        $table->addField('<a href="' . url('topic/' . $row['id'], find_index('page')) . '">' . secure($row['title'], 'html') . '</a>');
                        $table->addField(format_datetime($row['created']) . '<br>by ' . $this->getUser($row['user']));
                        $table->addField(format_datetime($row['updated']) . '<br>by ' . $this->getUser($row['last_user']));
                    }, $table);
                    $this->table = $table;
                    parent::show($type . '/forum');
                } else if (has_value('forum_action')) {
                    $forum_action = find_value('forum_action');
                    if ($forum_action == 'create' && $this->loggedIn()) {
                        $result = query("SELECT F.`name`, G.`name` AS `group` FROM `" . prefix('forum') . "` F LEFT JOIN `" . prefix('forum_group') . "` G ON G.`id`=F.`group` WHERE F.`id`=$forum");
                        if ($row = $result->fetch_array())
                            $s['head'] = 'Post in ' . secure($row['group'] . ' > ' . $row['name'], 'html') . ' forum';
                        $form = new Form();
                        $form->add('Title', 'text', 'title');
                        $form->add('Submit', 'submit');
                        if ($form->received() && $form->check()) {
                            $title = secure($form->get('title'));
                            $user = $this->userId();
                            $result = query("INSERT INTO `" . prefix('topic') . "` SET `user`=$user, `forum`=$forum, `created`=NOW(), `updated`=NOW(), `last_user`=$user, `title`='$title'");
                            if ($result) {
                                array_pop($s['h']);
                                $s['h'][] = 'topic';
                                $s['h'][] = insert_id();
                                redirect_current();
                            }
                        }
                        $this->form = $form;
                        parent::show($type . '/create_topic');
                    } else if ($forum_action == 'topic') {
                        if (has_value('topic')) {
                            $topic = (int)find_value('topic');
                            if ($this->loggedIn()) {
                                $form = new Form();
                                $form->add('Comment', 'textarea', 'content');
                                $form->add('Submit', 'submit');
                                if ($form->received() && $form->check()) {
                                    $content = secure($form->get('content'));
                                    $user = $this->userId();
                                    query("INSERT INTO `" . prefix('post') . "` SET `topic`=$topic, `user`=$user, `created`=NOW(), `edited`=NOW(), `content`='$content'");
                                    query("UPDATE `" . prefix('topic') . "` SET `updated`=NOW(), `last_user`=$user WHERE `id`=$topic");
                                    $form = new Form();
                                    $form->setClear(true);
                                    $form->add('Comment', 'textarea', 'content');
                                    $form->add('Submit', 'submit');
                                }
                                $this->form = $form;
                            }
                            $result = query("SELECT T.`title`, F.`name` AS `forum`, G.`name` AS `group` FROM `" . prefix('topic') . "` T LEFT JOIN `" . prefix('forum') . "` F ON F.`id`=T.`forum` LEFT JOIN `" . prefix('forum_group') . "` G ON G.`id`=F.`group` WHERE T.`id`=$topic");
                            if ($row = $result->fetch_array()) {
                                $s['head'] = secure($row['title'], 'html');
                                $this->forum = $row['forum'];
                                $this->group = $row['group'];
                            }
                            $posts = array();
                            $pager = new Pager(8);
                            $pager->query("*, UNIX_TIMESTAMP(`created`) AS `created`, UNIX_TIMESTAMP(`edited`) AS `edited`", "`" . prefix('post') . "` WHERE `topic`=" . (int)find_value('topic') . " ORDER BY `created` ASC");
                            $this->pager = $pager;
                            parent::show($type . '/topic');
                        }
                    }
                }
            } else {
                if (!$this->page($forum))
                    parent::show($type . '/' . $forum);
            }
        } else {
            parent::show($type . '/main');
        }
    }

    function userId() {
        return 0;
    }

    function getUser($id) {
        return '';
    }

    function page($page) {
        if ($page == 'login')
            return $this->login();
        if ($page == 'logout')
            return $this->logout();
        return false;
    }

    function login() {
        return false;
    }

    function logout() {
        return false;
    }

    function loggedIn() {
        return false;
    }

    function getGroups() {
        $array = array();
        $result = query("SELECT * FROM `" . prefix('forum_group') . "`");
        while ($row = $result->fetch_array())
            $array[] = $row;
        return $array;
    }

    function getForums($group) {
        $array = array();
        $result = query("SELECT * FROM `" . prefix('forum') . "` WHERE `group`=$group");
        while ($row = $result->fetch_array()) {
            $id = $row['id'];
            $subresult = query("SELECT UNIX_TIMESTAMP(`updated`) AS `updated`, `last_user` FROM `" . prefix('topic') . "` WHERE `forum`=$id ORDER BY `updated` DESC LIMIT 1");
            if ($subrow = $subresult->fetch_array()) {
                $row['updated'] = $subrow['updated'];
                $row['last_user'] = $subrow['last_user'];
            }
            $array[] = $row;
        }
        return $array;
    }

    function getPager() {
        return $this->pager;
    }

    function getAccount() {
        return null;
    }

    function getTable() {
        return $this->table;
    }

    function getForum() {
        return $this->forum;
    }

    function getGroup() {
        return $this->group;
    }

    function getForm() {
        return $this->form;
    }
}
