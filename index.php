<?php 

function encode_string( $string )
{
	$encoded = '';
	$len = strlen( $string );

	for ( $i = 0; $i < $len; $i++ )
	{
		$encoded .= "&#" . ord( $string[$i] ) . ';';
	}

	return $encoded;
}

function minify_css( $style )
{
	// Strips Comments
	$style = preg_replace( '!/\*.*?\*/!s', '', $style );
	$style = preg_replace( '/\n\s*\n/', "\n", $style );

	// Minifies
	$style = preg_replace( '/[\n\r \t]/',' ', $style );
	$style = preg_replace( '/ +/', ' ', $style );
	$style = preg_replace( '/ ?([,:;{}]) ?/', '$1', $style );

	return $style;
}

function minify_js( $code )
{
	// remove white spaces
	$code = preg_replace( '/((?<!\/)\/\*[\s\S]*?\*\/|(?<!\:)\/\/(.*))/', '', $code );
	$code = preg_replace( "/\n|\r|\t/", "", $code );

	return $code;
}

// file output
$file_output = isset( $_GET['output'] ) && 'file' == $_GET['output'];

// start output cache
if ( $file_output )
{
	// set headers
	header('Content-Encoding: gzip');

	// start buffering
	ob_start( 'ob_gzhandler' );
}

?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Nabeel Molham Rosdhy Resume</title>
		<?php
		// CSS Style

		// fonts 
		$style = file_get_contents( 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600,700' );

		// style
		$style .= file_get_contents( 'css/resume.css' );
		?>
		<style type="text/css"><?php echo minify_css( $style ); ?></style>
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>

		<!-- Back to Top -->
		<!-- <a href="#top" id="go-top"><img src="images/top-arrow.png" alt="" /> Back to top</a> -->

		<!-- Main Header -->
		<header id="header">
			<div class="wrap">
				<h1 class="main-head">Nabeel Molham</h1>
	
				<nav class="sections-nav">
					<h2 class="nav-title">Navigation</h2>
					<ul class="clearfix"></ul>
				</nav><!-- #sections-nav -->
			</div><!-- .wrap -->
		</header><!-- #header -->

		<!-- About Me -->
		<section id="about-me" class="section" data-nav="true">
			<div class="wrap">
				<h2 class="section-title">About me</h2>

				<p>My name is <strong>Nabeel Molham</strong>, a freelance web developer focused on WorPress which I LOVE. I worked with few companies inside and outside Egypt, and I all started at the end of 2003 with Micromedia Flash 5.0 :D.</p>

				<?php $me_img = 'images/me.jpg'; ?>
				<div class="photo"><img src="data:<?php echo mime_content_type( $me_img ), ';base64,', base64_encode( file_get_contents( $me_img ) ); ?>" alt="" class="image" /></div>

				<dl class="basic-info">
					<dt>Name</dt>
					<dd><strong>Nabeel Molham Rosdhy Adb El-Malek</strong></dd>
					<dd class="sep"></dd>

					<dt>Born</dt>
					<dd>1 March 1988</dd>
					<dd class="sep"></dd>

					<dt>Address</dt>
					<dd><?php echo encode_string( 'Mansoura, Al-Dakahlia, Egypt.' ); ?></dd>
					<dd class="sep"></dd>

					<dt>Email</dt>
					<dd><?php echo encode_string( 'n.molham@gmail.com' ); ?></dd>
					<dd class="sep"></dd>

					<dt>Website</dt>
					<dd><a href="http://nabeel.molham.me/" target="_blank">nabeel.molham.me</a></dd>
					<dd class="sep"></dd>

					<dt>Mobile</dt>
					<dd><?php echo encode_string( '+201007221498' ); ?></dd>
					<dd class="sep"></dd>

					</dl><!-- .basic-info -->
			</div><!-- .wrap -->
		</section><!-- #about-me -->

		<!-- Detail info -->
		<section id="work-experiences" class="more-info section" data-nav="true">
			<div class="wrap">
				<h2 class="section-title">Work Experiences</h2>

				<div class="knowlage clearfix">
					<div class="qualifies col">
						<section class="sub-section jobs">
							<h3 class="section-title">Employment</h3>

							<article class="entry">
								<h3 class="section-title">Training Instructor</h3>
								<span class="entry-time">Present ( Part-time )</span>

								<div class="entry-content">
									<p>Web and WordPress development Instructor at <a href="http://www.facebook.com/WorkspaceEG" target="_blank">Workspace</a></p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title">Freelance Web/WordPress Developer</h3>
								<span class="entry-time">Present</span>

								<div class="entry-content">
									<p>WordPress development is the best thing I like to do, themes, plugins and deep integrations, also working on other platforms and front-end development.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title">Development Team Leader, Senior Web Developer</h3>

								<div class="entry-content">
									<p>Working at <a href="http://eprisma.com" target="_blank">Eprisma</a> as a Senior at first then a Development team leader, and I was incharge of company products and other web projects development and mobile API integrations.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title">Freelance RIA Developer</h3>

								<div class="entry-content">
									<p>Rich Internet Application development with Adobe Flash, Adobe Flash Builder "was known as Adobe Flex", ActionScript 3.0 and MXML.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->
						</section><!-- .jobs -->

						<section class="sub-section education">
							<h3 class="section-title">Education</h3>

							<article class="entry">
								<h3 class="section-title">Bachelor of Commerce</h3>
								<span class="entry-time">2004 - 2011</span>

								<div class="entry-content">
									<p>From Mansoura University.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->
						</section><!-- .education -->
					</div><!-- .qualifies -->

					<div class="skills col">
						<h3 class="section-title">Skills</h3>

						<section class="sub-section">
							<h3 class="section-title">Operating Systems</h3>

							<article class="entry">
								<h4 class="section-title">Microsoft Windows</h4>
								<div class="bar">
									<div class="percent" style="width:90%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">Linux Desktop</h4>
								<div class="bar">
									<div class="percent" style="width:80%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">Linux Server</h4>
								<div class="bar">
									<div class="percent" style="width:80%;"></div>
								</div>
							</article>
						</section>

						<section class="sub-section bars-blue">
							<h3 class="section-title">Web Development</h3>

							<article class="entry">
								<h4 class="section-title">HTML5 &amp; CSS3</h4>
								<div class="bar">
									<div class="percent" style="width:100%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">JavaScript &amp; jQuery</h4>
								<div class="bar">
									<div class="percent" style="width:95%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">PHP</h4>
								<div class="bar">
									<div class="percent" style="width:100%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">WordPress</h4>
								<div class="bar" style="width:95%;"></div>
								<div class="bar">
									<div class="percent" style="width:95%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">ASP .NET 3.5</h4>
								<div class="bar">
									<div class="percent" style="width:50%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">ActionScript 3.0</h4>
								<div class="bar">
									<div class="percent" style="width:75%;"></div>
								</div>
							</article>
						</section>

						<section class="sub-section bars-green">
							<h3 class="section-title">Desktop &amp; Mobile</h3>

							<article class="entry">
								<h4 class="section-title">Adobe AIR</h4>
								<div class="bar">
									<div class="percent" style="width:50%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">PhoneGap</h4>
								<div class="bar">
									<div class="percent" style="width:70%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">Titanium SDK</h4>
								<div class="bar">
									<div class="percent" style="width:50%;"></div>
								</div>
							</article>
						</section>

						<section class="sub-section">
							<h3 class="section-title">Database</h3>

							<article class="entry">
								<h4 class="section-title">MySQL - MarinaDB</h4>
								<div class="bar">
									<div class="percent" style="width:100%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">Oracle</h4>
								<div class="bar">
									<div class="percent" style="width:65%;"></div>
								</div>
							</article>

							<article class="entry">
								<h4 class="section-title">MS SQL Server</h4>
								<div class="bar">
									<div class="percent" style="width:50%;"></div>
								</div>
							</article>
						</section>

					</div><!-- .skills -->
				</div><!-- .knowlage -->
			</div><!-- .wrap -->
		</section><!-- #work-experiences -->

		<!-- Detail info -->
		<section id="portfolio" class="more-info section" data-nav="true">
			<div class="wrap">
				<h2 class="section-title">Portfolio</h2>

				<div class="knowlage clearfix">
					<div class="qualifies col">
						<section class="sub-section big-projects">
							<h3 class="section-title">Latest Big Projects</h3>

							<article class="entry">
								<h3 class="section-title"><a href="http://tveez.me" target="_blank">TVeeZ.me</a></h3>
								<strong class="entry-time">TV Guide</strong>

								<div class="entry-content">
									<p>A web and mobile application, WordPress and mobile integration API.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://no5rog.com" target="_blank">No5rog</a></h3>
								<strong class="entry-time">Entertainment Guide</strong>

								<div class="entry-content">
									<p>A web and mobile application, WordPress and mobile integration API.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://elmafia.com" target="_blank">El-Mafia</a></h3>
								<strong class="entry-time">Test-based Game</strong>

								<div class="entry-content">
									<p>Online game made from scratch with PHP and MySQL and payment gateways like Facebook, cashU and OneCard. Basic layout based on the client request. Project toke almost a year.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

						</section><!-- .big-projects -->

						<section class="sub-section other-projects">
							<h3 class="section-title">Some of Other Projects</h3>

							<article class="entry">
								<h3 class="section-title"><a href="http://coingy.com" target="_blank">CoinExchange</a></h3>
								<strong class="entry-time">Digital Coins Exchanging</strong>

								<div class="entry-content">
									<p>Wordpress plugin integrated digital coins APIs like Dogecoin, Bitcoin, Litecoin etc</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://entirej.com" target="_blank">EntireJ API</a></h3>
								<strong class="entry-time">Customers Support System</strong>

								<div class="entry-content">
									<p>Wordpress plugin integrated with <a href="https://github.com/harvesthq/api" target="_blank">Harvest API</a></p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://utmegypt.com" target="_blank">ProjectTitle</a></h3>
								<strong class="entry-time">Company</strong>

								<div class="entry-content">
									<p>Colors Painting Company, WordPress theme.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://orignalegypt.com" target="_blank">Original Egypt</a></h3>
								<strong class="entry-time">Company</strong>

								<div class="entry-content">
									<p>A Juice Company, WordPress theme.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://hamedabdalla.com" target="_blank">Dr. Hamed Abd Allah</a></h3>
								<strong class="entry-time">Portfolio</strong>

								<div class="entry-content">
									<p>Blog, protfolio and patients Q&amp;A, WordPress theme and plugins.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://hanisoliman.com" target="_blank">Hani Soliman</a></h3>
								<strong class="entry-time">Portfolio</strong>

								<div class="entry-content">
									<p>Creative Director Portfolio site, WordPress theme and plugins.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://moddelus.com" target="_blank">Moddelus</a></h3>
								<strong class="entry-time">Company</strong>

								<div class="entry-content">
									<p>Models Agents Company, WordPress theme &amp; plugins.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://chezedy.com" target="_blank">Chezedy</a></h3>
								<strong class="entry-time">Café</strong>

								<div class="entry-content">
									<p>Café website, mobile version integrated with no5rog API.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://sdotmedia.com" target="_blank">Sdot Media</a></h3>
								<strong class="entry-time">Company</strong>

								<div class="entry-content">
									<p>Company proftolio, WordPress theme.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://couranto.com" target="_blank">Couranto</a></h3>
								<strong class="entry-time">News</strong>

								<div class="entry-content">
									<p>News website, WordPress theme and plugins.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://masrena.org.eg" target="_blank">Masrena</a></h3>
								<strong class="entry-time">Foundation</strong>

								<div class="entry-content">
									<p>Event site for 25 revolution’s youth made with WordPress. Did the theme developing and layout including plug-ins developing.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://kets.sd" target="_blank">Kenana Engineering and Technical Services</a></h3>
								<strong class="entry-time">Company</strong>

								<div class="entry-content">
									<p>WordPress theme.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://gangsboss.com" target="_blank">Gangs Boss</a></h3>
								<strong class="entry-time">Text-based Game</strong>

								<div class="entry-content">
									<p>Online game continued and completed the exiting project and layout. Was a subproject from elmafia.com.</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

							<article class="entry">
								<h3 class="section-title"><a href="http://eprisma.com" target="_blank">Eprisma</a></h3>
								<strong class="entry-time">Company</strong>

								<div class="entry-content">
									<p>Software solution company web site made with flash platform ( Adobe Flex ).</p>
								</div><!-- .entry-content -->
							</article><!-- .entry -->

						</section><!-- .other-projects -->
						
					</div><!-- .qualifies -->
				</div><!-- .knowlage -->
			</div><!-- .wrap -->
		</section><!-- #portfolio -->
		
		<!-- Contacts & footer -->
		<footer id="contacts" class="section" data-nav="true">
			<div class="wrap">
				<h2 class="section-title">Contact</h2>
				<p>Drop me a line using contact form below.</p>

				<div class="contact-section contact-info">
					<h3 class="section-title">Contact info</h3>

					<dl>
						<dt>Address:</dt>
						<dd>
							<?php echo encode_string( 'No. 24' ); ?><br/>
							<?php echo encode_string( 'EL-Tahreer St., El-Galaa St.,' ); ?><br/>
							<?php echo encode_string( 'Mansoura, Al-Dakahlia, Egypt.' ); ?>
						</dd>
						<dd class="sep"></dd>

						<dt>Mobile:</dt>
						<dd><?php echo encode_string( '+201007221498' ); ?></dd>
						<dd class="sep"></dd>

						<dt>Email:</dt>
						<dd><a href="mailto:<?php echo encode_string( 'n.molham@gmail.com' ); ?>"><?php echo encode_string( 'n.molham@gmail.com' ); ?></a></dd>
						<dd class="sep"></dd>
					</dl>

					<ul class="social">
						<li class="item"><a href="http://github.com/N-Molham" title="GitHub" target="_blank" class="link git">GitHub</a></li>
						<li class="item"><a href="http://plus.google.com/+NabeelMolham" title="Goole+" target="_blank" class="link google">Google+</a></li>
						<li class="item"><a href="http://fb.com/nabeel.molham" title="Facebook" target="_blank" class="link facebook">Facebook</a></li>
						<li class="item"><a href="http://twitter.com/NabeelMolham" title="Twitter" target="_blank" class="link twitter">Twitter</a></li>
						<li class="item"><a href="http://instagram.com/nabeelmolham" title="Instagram" target="_blank" class="link instagram">Instagram</a></li>
						<li class="item"><a href="http://www.youtube.com/user/nabeelmolham" title="Youtube" target="_blank" class="link youtube">Youtube</a></li>
					</ul><!-- .social -->
				</div><!-- .contact-info -->

				<div class="contact-section contact-form">
					<form action="#" method="post" data-mail="<?php echo encode_string( 'n.molham@gmail.com' ); ?>">
						<label for="contact-name">Name</label>
						<p><input type="text" name="name" id="contact-name" required="required" /></p>

						<label for="contact-email">Email</label>
						<p><input type="email" name="email" id="contact-email" required="required" /></p>

						<label for="contact-msg">Message</label>
						<p><textarea name="msg" id="contact-msg" required="required"></textarea></p>

						<input type="submit" id="contact-send" value="Send" />
					</form>
				</div><!-- .contact-form -->
			</div><!-- .wrap -->

			<div class="wrap">
				<div class="footer clearfix">
					<p class="final">Hope you like it &#8730;</p>
	
					<a href="#" class="top">Github</a>
				</div><!-- .footer -->
			</div><!-- .wrap -->
		</footer><!-- #contacts -->

		<!-- Scripts -->
		<?php 
		// jQuery lib
		$scripts = file_get_contents( 'http://code.jquery.com/jquery.min.js' );

		// resume js
		$scripts .= minify_js( file_get_contents( 'js/script.js' ) );
		?>
		<script><?php echo $scripts; ?></script>
	</body>
</html>
<?php 

// output flush
if ( $file_output )
{
	// get flush
	$content = ob_get_flush();

	// clean buffer
	ob_clean();

	// save content
	file_put_contents( 'index.html', $content );

	// redirect to result file
	header( 'location: resume.html' );
	die();
}
