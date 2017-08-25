<html>
        <head>
                <title>CodeIgniter Tutorial</title>
        </head>
        <body>
        		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">	
        			<ul class="nav navbar-nav navbar-right">
						<?php if (isset($_SESSION['username']) && $_SESSION['logged_in'] === true) : ?>
							<li><a href="<?= base_url('logout') ?>">Logout</a></li>
						<?php else : ?>
							<li><a href="<?= base_url('register') ?>">Register</a></li>
							<li><a href="<?= base_url('login') ?>">Login</a></li>
							<li><a href="<?= base_url('logout') ?>">Logout</a></li>
						<?php endif; ?>
					</ul>
 				</div>
                <h1>Simple CRUD</h1>
                <p><a href="<?php echo site_url('news'); ?>">Home</a> | <a href="<?php echo site_url('news/create'); ?>">Add News</a></p>