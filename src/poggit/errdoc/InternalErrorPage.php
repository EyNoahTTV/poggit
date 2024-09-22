<?php

/*
 * Poggit
 *
 * Copyright (C) 2016-2018 Poggit
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace poggit\errdoc;

use poggit\Mbd;
use poggit\Meta;
use poggit\module\Module;
use poggit\account\Session;
use function http_response_code;
use function readfile;
use const poggit\RES_DIR;

class InternalErrorPage extends Module {
    public function output() {
        http_response_code(500);
        ?>
      <!-- Request ID: <?= $_REQUEST["id"] ?? Meta::getRequestId() ?> -->
      <html>
      <head
          prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# object: http://ogp.me/ns/object# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">
        <style type="text/css"><?php
            try {
                $session = Session::getInstance();
                if($session === null || !$session->isLoggedIn()) {
                    readfile(RES_DIR . "style.css");
                }
                if($session->getLogin()["opts"]->darkMode ?? false) {
                    readfile(RES_DIR . "style-dark.css");
                } else {
                    readfile(RES_DIR . "style.css");
                }
            } catch (\Exception $e){
                readfile(RES_DIR . "style.css");
            } ?></style>
        <script>
            <?php Mbd::analytics() ?>
            <?php Mbd::gaCreate() ?>
            ga('send', 'event', 'Special', 'Error', window.location.pathname, {nonInteraction: true});
        </script>
        <title>500 Internal Server Error</title>
      </head>
      <body>
      <div id="body">
        <h1>500 Internal Server Error</h1>
        <p>A server internal error occurred. Please use this request ID for reference if you need support:
          <code class="code"><?= htmlspecialchars($_REQUEST["id"] ?? Meta::getRequestId(), ENT_QUOTES, 'UTF-8') ?></code></p>
        <p>Logging out may solve the problem.
          <span class="action" onclick="location.assign('<?= Meta::root() ?>logout')">Have a try</span></p>
        <br />
        <p>If the error persists, contact the poggit team with the above request ID:</p>
        <ul>
          <li>Via <a href="https://github.com/poggit/poggit/issues/new">GitHub - Report Issues</a></li>
          <li>Via <a href="https://discord.gg/DYSEf2WGPQ">Discord - Chat Server</a></li>
          <li>Via <a href="mailto:team@pmmp.io?subject=Poggit%20500%20ISE%20-%20<?= htmlspecialchars($_REQUEST["id"] ?? Meta::getRequestId(), ENT_QUOTES, 'UTF-8') ?>">Email</a></li>
      </div>
      </body>
      </html>
        <?php
    }
}
