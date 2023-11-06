<?php if (!isset($_SESSION)) session_start(); 

include '../lib/config.php';
include '../lib/database.php';

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CharitEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="icon" href="../lib/imgs/charitease_icon.png">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;700&display=swap">

  <style>
    body {
      font-family: 'Red Hat Display', sans-serif;
    }
  </style>
    <style>
    .conversation {
      max-height: 400px;
      overflow-y: auto;
    }

    .conversation-bubble {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      margin-bottom: 10px;
      max-height: 100px;
      overflow-y: auto;
    }

    .conversation-bubble p {
      margin: 5px;
      padding: 10px;
      border-radius: 20px;
    }

    .conversation-bubble .timestamp {
      font-size: 0.8rem;
      color: #999;
      margin-top: 5px;
    }

    .incoming p {
      background-color: #f5f5f5;
      color: #333;
      align-self: flex-start;
    }

    .outgoing p {
      background-color: #007bff;
      color: #fff;
      align-self: flex-end;
    }

    .conversation-form {
      margin-top: 20px;
    }

    .icon {
      margin-right: 5px;
    }
  </style>
    
</head>
<body>
    <?php
        if (!isset($_SESSION['user']) && ($_SESSION['user'] != 'charity')) {
            location('../reg/login.php');
        }
    ?>

    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
        <div class="navbar-start">
            <a href="" class="navbar-item">
            <img src="../lib/imgs/charitease_icon.png" alt="Logo">
            <?php 
				$role = "";
				$icon = "";
				if (isset($_SESSION['user'])) {
					$role = " // " . ucfirst($_SESSION['user']);

					$icon = match ($_SESSION['user']) {
						'donor' => "volunteer_activism",
						'charity' => "real_estate_agent",
						'admin' => "shield_person",
						default => ""
					};
				}
            ?>
            <h1 class="subtitle">CharitEase <span class="material-symbols-outlined"><?= $icon; ?></span><?= $role; ?></h1>
            </a>
        </div>
        <span class="navbar-burger burger" data-target="navbarMenu">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </span>
        </div>

        <div id="navbarMenu" class="navbar-menu">
        <div class="navbar-end">
                <a href="../index.php" class="navbar-item"><span class="material-symbols-outlined">home</span>Home</a>
                <?php
                if (isset($_SESSION['user'])) {
                    if ($_SESSION['user'] == 'donor') {
                ?>
                    <a href="../dnr/donate.php" class="navbar-item"><span class="material-symbols-outlined">real_estate_agent</span>List</a>
                    <a href="../dnr/maps.php" class="navbar-item"><span class="material-symbols-outlined">home_pin</span>Map</a>
                    <a href="#" class="navbar-item"><span class="material-symbols-outlined">chat</span>Chat</a>
                    <a href="../dnr/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
                <?php 
                    } else if ($_SESSION['user'] == 'charity') {
                ?>
                <!-- ORGANIZATION BUTTON -->
                    <a href="../org/donors.php" class="navbar-item"><span class="material-symbols-outlined">chat</span>Chat</a>
                    <a href="../org/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
                <?php 
                    } else if ($_SESSION['user'] == 'admin') {
                ?>
                <!-- ADMIN BUTTONS -->
                <a href="../adm/dashboard.php" class="navbar-item"><span class="material-symbols-outlined">dashboard</span>Panel</a>
                <?php 
                    }
                ?>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a href="" class="navbar-link"><span class="material-symbols-outlined">account_circle</span><?= $_SESSION['name']; ?></a>
                    <div class="navbar-dropdown">
                    <a href="../reg/settings.php" class="navbar-item"><span class="material-symbols-outlined">manage_accounts</span>Settings</a>
                    <a href="../reg/logout.php" class="navbar-item"><span class="material-symbols-outlined">logout</span>Logout</a>
                    </div>
                </div>
                <?php 
                } else {
                ?>
                    <a href="../reg/register.php" class="navbar-item"><span class="material-symbols-outlined">how_to_reg</span>Register</a>
                    <a href="../reg/login.php" class="navbar-item"><span class="material-symbols-outlined">login</span>Login</a>
                <?php } ?>
                <span class="navbar-item"></span>
                <span class="navbar-item"></span>
            </div>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
        $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {
                const target = el.dataset.target;
                const $target = document.getElementById(target);
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
        });
    </script>

    <section class="section">
        <!-- sidebar list of people -->
        <?php
            $id = $_SESSION['id'];
            $users = [];
            $processedReceiverIds = [];

            $db = new Database();

            $chatq = "SELECT * FROM `tblchats` WHERE sender_id = '$id' ORDER BY timestamp ASC";
            $chatr = $db->query($chatq);
            
            if ($chatr) {
                while ($chatas = $chatr->fetch_assoc()) {
                    //$users[] = $chatas;

                    $receiver_id = $chatas['receiver_id'];

                    $users[] = $chatas;

                    // Check if receiver_id has already been processed
                    if (!in_array($receiver_id, $processedReceiverIds)) {
                        // Retrieve client_name for the unique receiver_id
                       $processedReceiverIds[] = $receiver_id;
                    }
                }
            }
        ?>
        <div class="columns">
            <div class="column is-one-fifth">
                <div class="box">
                    <aside class="menu">
                        <p class="menu-label">Chats</p>
                        <ul class="menu-list">
                            <?php foreach ($users as $user) { 
                                 $userq = "SELECT client_name FROM `tblclients` WHERE client_id = '$receiver_id'";
                                 $userr = $db->query($userq);
                     
                                 if ($userr) {
                                     $user_assoc = $userr->fetch_assoc();
                                     $client_name = $user_assoc['client_name'];
                                 }

                                 $receiver_id = $user['receiver_id'];

                                 if (in_array($receiver_id, $processedReceiverIds)) {

                            ?>
                            <li><a href="#<?= $user['receiver_id']; ?>"><span class="material-symbols-outlined">person</span><?= $client_name; ?></a></li>
                            <?php }
                            } ?>
                        </ul>
                    </aside>
                </div>
            </div>

            <div class="column">
                <div id="startup" class="content">
                    <p class="title has-text-centered">No Chats Selected</p>
                </div>
                <?php foreach ($users as $user) { 
                    
                    $bubbleClass = ($user['initiated_by'] == 'c') ? 'incoming charity-a' : 'outgoing charity-a';
                    $personName = ($user['initiated_by'] == 'c') ? 'Charity' : 'You';
                    
                    $userq = "SELECT client_name FROM `tblclients` WHERE client_id = '$receiver_id'";
                    $userr = $db->query($userq);
                     
                    if ($userr) {
                        $user_assoc = $userr->fetch_assoc();
                        $client_name = $user_assoc['client_name'];
                    }
                ?>
                <div id="<?= $user['receiver_id']; ?>" class="content is-hidden">
                    <div class="box">
                        <p><?= $client_name; ?></p>
                        <div class="conversation">
                        <?php foreach ($users as $message) { 
                        if ($message['receiver_id'] == $user['receiver_id']) { ?>
                            <div class="conversation-bubble <?= $message['initiated_by'] == 'c' ? 'incoming' : 'outgoing'; ?>">
                                <p>
                                    <?= $message['message']; ?> <br>
                                    <span class="timestamp"><?= "[" . ($message['initiated_by'] == 'c' ? 'Charity' : 'You') . "] " .  $message['timestamp']; ?></span>
                                </p>
                            </div>
                            <?php } ?>
                        <?php } ?>
                        </div>

                        <form class="conversation-form" method="POST" autocomplete="off">
                            <div class="field is-grouped">
                                <div class="control is-expanded">
                                <input class="input" type="text" placeholder="Type a message..." name="msgbox">
                                </div>
                                <div class="control">
                                <button class="button is-primary" name="send" type="submit">Send</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <script>
		document.addEventListener('DOMContentLoaded', function () {
			var tabs = document.querySelectorAll('.menu-list a');

			for (var i = 0; i < tabs.length; i++) {
				tabs[i].addEventListener('click', function (event) {
					event.preventDefault();

					var target = event.target.getAttribute('href').replace('#', '');

					var contents = document.querySelectorAll('.content');
					for (var j = 0; j < contents.length; j++) {
						contents[j].classList.add('is-hidden');
					}

					document.getElementById(target).classList.remove('is-hidden');

					var links = document.querySelectorAll('.menu-list a');
					for (var k = 0; k < links.length; k++) {
						links[k].classList.remove('is-active');
					}

					event.target.classList.add('is-active');
				});
			}
		});
	</script>

    <footer class="section">
        <div class="container">
        <div class="pb-5 is-flex is-flex-wrap-wrap is-justify-content-between is-align-items-center">
            <div class="mr-auto mb-1">
            <a href="#" class="is-inline-block">
                <img src="../lib/imgs/charitease_icon.png" alt="" class="image is-64x64">
            </a>
            </div>
            <div>
            <ul class="is-flex is-flex-wrap-wrap is-align-items-center is-justify-content-center">
                <li class="mr-4"><a href="../com/about.php" class="button is-white">About</a></li>
                <li class="mr-4"><a href="../com/company.php" class="button is-white">Company</a></li>
            </ul>
            </div>
        </div>
        </div>
        <div class="pt-5" style="border-top: 1px solid #dee2e6;"></div>
        <div class="container">
        <div class="is-flex-tablet is-justify-content-between is-align-items-center">
            <p>All rights reserved © CharitEase 2023</p>
            <div class="py-2 is-hidden-tablet"></div>
        </div>
        </div>
    </footer>

    <?php include '../lib/alert.php'; ?>
</body>
</html>