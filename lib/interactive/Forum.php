<?php

import_lib('core/session');
import_lib('utils/MultiFormat');

class Forum extends MultiFormat {
    function __construct() {
        parent::__construct('forum');
    }

    function setup() {
    }

    function show($type = 'default') {
        $s['suburl'] = array('forum', 'topic', 'page');
        if (has_value('forum')) {
            $forum = get_value('forum');
            switch ($forum) {
            case 'profile':
                parent::show($type . '/profile');
                break;
            case 'settings':
                parent::show($type . '/settings');
                break;
            default:
                if (has_value('topic'))
                    parent::show($type . '/topic');
                else
                    parent::show($type . '/forum');
                break;
            }
        } else {
            parent::show($type . '/main');
        }
    }
}
