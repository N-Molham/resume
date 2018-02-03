<?php
/**
 * Index page
 */
if ( '' === session_id() )
{
	// start session if not already started
	session_start();
}

// file output
$file_output = 'file' === filter_input( INPUT_GET, 'output', FILTER_SANITIZE_STRING );
$codeable_refresh = 'yes' === filter_input( INPUT_GET, 'refresh', FILTER_SANITIZE_STRING );

if ( !isset( $_SESSION['force_counter'] ) )
{
	// prevent many refreshing processes
	$_SESSION['force_counter'] = 0;
}

$more_than_enough = $_SESSION['force_counter'] > 5;

if ( false === $more_than_enough && ( $file_output || $codeable_refresh ) )
{
	// increase counter
	$_SESSION['force_counter']++;
}

// file location absolute path
$path = __DIR__;

// encoding cache
$GLOBALS['encoding_cache'] = [];

// codeable info
$codeable_id = '18791';
$codeable_url = 'https://api.codeable.io/users/' . $codeable_id;

if ( $more_than_enough || ( false === $codeable_refresh && isset( $_SESSION['codeable_profile'] ) ) )
{
	// load from cache
	$codeable_profile = $_SESSION['codeable_profile'];
}
else
{
	// fetch fresh copy
	$codeable_profile             = json_decode( @file_get_contents( $codeable_url ) );
	$_SESSION['codeable_profile'] = $codeable_profile;
}

if ( $more_than_enough || ( false === $codeable_refresh && isset( $_SESSION['codeable_reviews'] ) ) )
{
	// load from cache
	$codeable_reviews = $_SESSION['codeable_reviews'];
}
else
{
	// fetch fresh copy
	$codeable_reviews             = array_merge(
		json_decode( @file_get_contents( $codeable_url . '/reviews' ) ),
		json_decode( @file_get_contents( $codeable_url . '/reviews?page=2' ) )
	);
	$_SESSION['codeable_reviews'] = $codeable_reviews;
}

/**
 * Encode passed string to ASCII code
 *
 * @param string $string
 *
 * @return string
 */
function encode_string( $string )
{
	$encoded  = '';
	$hash_key = md5( $string );
	if ( isset( $GLOBALS['encoding_cache'][ $hash_key ] ) )
	{
		return $GLOBALS['encoding_cache'][ $hash_key ];
	}

	for ( $i = 0, $len = strlen( $string ); $i < $len; $i++ )
	{
		$encoded .= "&#" . ord( $string[ $i ] ) . ';';
	}

	// cache & return
	$GLOBALS['encoding_cache'][ $hash_key ] = $encoded;

	return $encoded;
}

/**
 * Minify CSS
 *
 * @param string $style
 *
 * @return string
 */
function minify_css( $style )
{
	// Strips Comments
	$style = preg_replace( '!/\*.*?\*/!s', '', $style );
	$style = preg_replace( '/\n\s*\n/', "\n", $style );

	// Minifies
	$style = preg_replace( '/[\n\r \t]/', ' ', $style );
	$style = preg_replace( '/ +/', ' ', $style );
	$style = preg_replace( '/ ?([,:;{}]) ?/', '$1', $style );

	return $style;
}

/**
 * Minify Javascript
 *
 * @param string $code
 *
 * @return string
 */
function minify_js( $code )
{
	// remove white spaces
	$code = preg_replace( '/((?<!\/)\/\*[\s\S]*?\*\/|(?<!\:)\/\/(.*))/', '', $code );
	$code = preg_replace( "/\n|\r|\t/", "", $code );

	return $code;
}

// start output cache
if ( false === $more_than_enough && $file_output )
{
	// set headers
	header( 'Content-Encoding: gzip' );

	// start buffering
	ob_start( 'ob_gzhandler' );
}

?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<title>Nabeel Molham Rosdhy Resume</title>
	<?php
	// CSS Style

	// fonts
	$style = file_get_contents( 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600,700' );

	// style
	$style .= file_get_contents( $path . '/css/resume.css' );
	?>

	<!-- Fonts + Main Style -->
	<style type="text/css"><?php echo minify_css( $style ); ?></style>

	<!-- Print View -->
	<style rel="stylesheet" type="text/css" media="print"><?php echo minify_css( file_get_contents( $path . '/css/print.css' ) ); ?></style>

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>    <![endif]-->
</head>
<body>

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

		<p>My name is <strong>Nabeel Molham</strong>, a freelance web developer, WordPress Specialist. I worked with few
			companies inside and outside Egypt, and I all started at the end of 2003 with Macromedia Flash 5.0 :D.</p>

		<?php $me_img = $path . '/images/avatar_new.jpg'; ?>
		<div class="photo">
			<img src="data:<?php echo mime_content_type( $me_img ), ';base64,', base64_encode( file_get_contents( $me_img ) ); ?>" alt="" class="image" />
		</div>

		<dl class="basic-info">
			<dt>Name</dt>
			<dd><strong>Nabeel Molham Rosdhy AdbElMalak</strong></dd>
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
				<section class="sub-section education">
					<h3 class="section-title">Australian Computer Society Inc. (ACS)</h3>

					<article class="entry">
						<h3 class="section-title"><a href="https://www.acs.org.au/msa/information-for-applicants.html">ICT Skills Assessment</a></h3>
						<span class="entry-time">2017 - 2019</span>

						<div class="entry-content">
							<p>Assessed to be suitable for migration under 261212 (Web Developer) of the ANZSCO Code.</p>
						</div><!-- .entry-content -->
					</article><!-- .entry -->
				</section><!-- .education -->

				<section class="sub-section jobs">
					<h3 class="section-title">Employment</h3>

					<article class="entry">
						<h3 class="section-title">WordPress Expert @ <a href="https://codeable.io/developers/nabeel-molham/" target="_blank">Codeable.io</a></h3>
						<span class="entry-time">Dec 2015 : Present</span>
					</article><!-- .entry -->

					<article class="entry">
						<h3 class="section-title">Contractor @ <a href="https://www.skyverge.com/" target="_blank">SkyVerge - Leading WooCommerce experts</a></h3>
						<span class="entry-time">Mar 2017 : Sep 2017</span>

						<div class="entry-content">
							<p>Maintaining, developing, and improving their line of well-known WooCommerce extension products.</p>
						</div><!-- .entry-content -->
					</article><!-- .entry -->

					<article class="entry">
						<h3 class="section-title">Freelance Web Developer, WordPress Specialist</h3>

						<div class="entry-content">
							<p>WordPress development is the best thing I like to do, themes, plugins and deep integrations, also working on other platforms and front-end development.</p>
						</div><!-- .entry-content -->
					</article><!-- .entry -->

					<article class="entry">
						<h3 class="section-title">Sr. Web Developer @ <a href="http://jobthemes.com" target="_blank">F-kar Web Designs, Inc</a></h3>
						<span class="entry-time">Dec 2013 : Oct 2015</span>

						<div class="entry-content">
							<p>Working on contract basis.</p>
						</div>
					</article><!-- .entry -->

					<article class="entry">
						<h3 class="section-title">Development Team Leader, Senior Web Developer</h3>
						<span class="entry-time">Feb 2009 : Dec 2013</span>

						<div class="entry-content">
							<p>Working at Eprisma as a Senior at first then a Development team leader, and I was incharge of company products and other web projects development and mobile API integrations.</p>
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
						<h4 class="section-title">HTML &amp; CSS</h4>
						<div class="bar">
							<div class="percent" style="width:100%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">SASS</h4>
						<div class="bar">
							<div class="percent" style="width:100%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">JavaScript</h4>
						<div class="bar">
							<div class="percent" style="width:100%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">jQuery</h4>
						<div class="bar">
							<div class="percent" style="width:90%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">Vue.js</h4>
						<div class="bar">
							<div class="percent" style="width:80%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">React &amp; AngularJS</h4>
						<div class="bar">
							<div class="percent" style="width:50%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">PHP</h4>
						<div class="bar">
							<div class="percent" style="width:100%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">WordPress Development</h4>
						<div class="bar">
							<div class="percent" style="width:100%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">Laravel Framework</h4>
						<div class="bar">
							<div class="percent" style="width:80%;"></div>
						</div>
					</article>
				</section>

				<section class="sub-section">
					<h3 class="section-title">Task Runners</h3>

					<article class="entry">
						<h4 class="section-title">Grunt</h4>
						<div class="bar">
							<div class="percent" style="width:75%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">Gulp</h4>
						<div class="bar">
							<div class="percent" style="width:50%;"></div>
						</div>
					</article>
				</section>

				<section class="sub-section bars-green">
					<h3 class="section-title">Version/Source Control</h3>

					<article class="entry">
						<h4 class="section-title">Git</h4>
						<div class="bar">
							<div class="percent" style="width:75%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">Mercurial</h4>
						<div class="bar">
							<div class="percent" style="width:75%;"></div>
						</div>
					</article>
				</section>

				<section class="sub-section bars-blue">
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
							<div class="percent" style="width:80%;"></div>
						</div>
					</article>

					<article class="entry">
						<h4 class="section-title">Titanium SDK</h4>
						<div class="bar">
							<div class="percent" style="width:40%;"></div>
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

<?php
if ( $codeable_profile && $codeable_reviews )
{
	?>
	<section id="codeable" class="codeable section" data-nav="true">
		<div class="wrap">
			<h2 class="section-title">Codeable.io</h2>

			<div class="contact-section">
				<div class="reviews col">
					<section class="sub-section">
						<h3 class="section-title">Latest <?php echo count( $codeable_reviews ); ?> Reviews</h3>

						<ul class="codeable-reviews">
							<?php foreach ( $codeable_reviews as $review ) : ?>
								<li class="project-review">
									<img class="profile-pic" alt="" src="<?php echo $review->reviewer->avatar->medium_url; ?>">
									<div class="review-info">
										<div class="project-rating">
											<span>Project rating:</span>
											<p><?php echo str_repeat( '<span class="rating-star"></span>', $review->score ); ?></p>
										</div>
										<p class="project-title"><?php echo $review->task_title; ?></p>
										<?php if ( $review->comment ): ?>
											<p class="project-comment">"<?php echo $review->comment; ?>"</p>
										<?php endif; ?>
										<div>
											<span class="client-name"><?php echo $review->reviewer->full_name; ?></span>,
											<span class="review-datetime"><?php echo date( 'd M Y', $review->timestamp ); ?></span>
										</div>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					</section>
				</div>

				<div class="profile col">
					<section class="sub-section">
						<h3 class="section-title">Profile</h3>

						<p><strong><?php echo round( (float) $codeable_profile->average_rating, 2 ); ?> / 5</strong>
							overall rating so far.</p>

						<div id="codeableBadge" style="display: none;">
							<div id="codeableBadgeInner">
								<div id="codeableBadgeProfile">
									<p id="codeableBadgeButton">
										<a id="codeableBadgeButtonLink" href="https://app.codeable.io/tasks/new?preferredContractor=<?php echo $codeable_id ?>">POST
											YOUR TASK</a>
									</p>
								</div>
							</div>
						</div>
						<script type="text/javascript" charset="utf-8">
							(function () {
								var po   = document.createElement( 'script' );
								po.type  = 'text/javascript';
								po.async = true;
								po.id    = 'codeableScript';
								po.src   = 'https://s3-us-west-2.amazonaws.com/cdn.codeable.io/scripts/badges-v.js?id=<?php echo $codeable_id?>';
								var s    = document.getElementsByTagName( 'script' )[ 0 ];
								s.parentNode.insertBefore( po, s );
							})();
						</script>
					</section>

					<section class="sub-section">
						<h3 class="section-title">Codeable.io Certificate</h3>

						<a href="https://nabeel.molham.me/blog/codeable-certificate/" target="_blank" title="Codebale Certificate">
							<img src="https://nabeel.molham.me/blog/wp-content/uploads/2016/03/codeable-certificate-300x212.png" alt="Codebale Certificate" />
						</a>
					</section>
				</div>
			</div>
		</div>
	</section><!-- Codeable.io profile -->
	<?php
}
?>

<!-- Contacts & footer -->
<footer id="contacts" class="section" data-nav="true">
	<div class="wrap">
		<h2 class="section-title">Contacts</h2>
		<p>Drop me a line anytime.</p>

		<div class="contact-section contact-info">
			<h3 class="section-title">Contact info</h3>

			<dl>
				<dt>Address:</dt>
				<dd>
					<?php echo encode_string( 'No. 24' ); ?><br />
					<?php echo encode_string( 'EL-Tahreer St., El-Galaa St.,' ); ?><br />
					<?php echo encode_string( 'Mansoura, Al-Dakahlia, Egypt.' ); ?>
				</dd>
				<dd class="sep"></dd>

				<dt>Mobile:</dt>
				<dd><?php echo encode_string( '+201007221498' ); ?></dd>
				<dd class="sep"></dd>

				<dt>Email:</dt>
				<dd>
					<a href="mailto:<?php echo encode_string( 'n.molham@gmail.com' ); ?>"><?php echo encode_string( 'n.molham@gmail.com' ); ?></a>
				</dd>
				<dd class="sep"></dd>

				<dt>C.V.:</dt>
				<dd><a href="http://nabeel.molham.me/resume/index.html" download="resume.html">Download Resume</a></dd>
				<dd class="sep"></dd>
			</dl>

		</div><!-- .contact-info -->

		<div class="contact-section contact-social">
			<h3 class="section-title">Social</h3>
			<?php
			$social_links = [
				'github'     => [
					'label'    => 'Github',
					'username' => 'N-Molham',
					'link'     => 'http://github.com/N-Molham',
					'icons'    => [ '#shape-github' ],
				],
				'googleplus' => [
					'label'    => 'Google+',
					'username' => 'NabeelMolham',
					'link'     => 'http://plus.google.com/+NabeelMolham',
					'icons'    => [ '#shape-googleplus' ],
				],
				'twitter'    => [
					'label'    => 'Twitter',
					'username' => '@NabeelMolham',
					'link'     => 'http://twitter.com/NabeelMolham',
					'icons'    => [ '#shape-twitter' ],
				],
				'facebook'   => [
					'label'    => 'Facebook',
					'username' => 'nabeel.molham',
					'link'     => 'http://facebook.com/nabeel.molham',
					'icons'    => [ '#shape-facebook' ],
				],
				'youtube'    => [
					'label'    => 'YouTube',
					'username' => 'nabeelmolham',
					'link'     => 'http://www.youtube.com/user/nabeelmolham',
					'icons'    => [ '#shape-youtube' ],
				],
				'codepen'    => [
					'label'    => 'CodePen',
					'username' => 'NabeelMolham',
					'link'     => 'http://codepen.io/NabeelMolham/',
					'icons'    => [ '#shape-codepen' ],
				],
			];
			?>
			<ul class="social-list">
				<?php foreach ( $social_links as $social_name => $social_info ) : ?>
					<li class="social-item-container">
						<div class="social-item">
							<a href="<?php echo $social_info['link']; ?>" class="social-<?php echo $social_name; ?>" target="_blank">
								<?php foreach ( $social_info['icons'] as $icon_selector ) : ?>
									<svg viewBox="0 0 100 100" class="shape-<?php echo $social_name; ?>">
										<use xlink:href="<?php echo $icon_selector; ?>"></use>
									</svg>
								<?php endforeach; ?>
								<span><?php echo $social_info['label']; ?>
									<span><?php echo $social_info['username']; ?></span></span> </a>
						</div>
					</li>
				<?php endforeach; ?>
			</ul><!-- .social -->
		</div><!-- .contact-social -->
	</div><!-- .wrap -->

	<div class="wrap">
		<div class="footer clearfix">
			<p class="final">Hope you like it &#8730;</p>

			<a href="http://github.com/N-Molham/resume" class="top" target="_blank">Github</a>
		</div><!-- .footer -->
	</div><!-- .wrap -->
</footer><!-- #contacts -->

<svg class="social-drawing">
	<defs>
		<path id="shape-email" d="M44.933,98.093C19.882,92.443,3.579,72.894,6.295,42.843C8.963,13.331,32.181-0.745,54.983,0.03
				c18.222,0.615,37.405,13.801,38.936,38.896c0.718,11.746-3.373,22.681-9.981,29.42c-8.577,8.746-19.542,10.468-28.546,9.201
				C41.647,75.631,27.999,66.319,28.748,46.28c0.265-7.127,3.676-14.744,9.178-19.788c3.56-3.269,9.475-5.933,15.366-5.743
				c6.368,0.208,11.371,3.154,14.656,7.464c5.83,7.661,5.674,19.918,5.456,21.124c0,0-0.04,0.302-0.095,0.712
				c-0.147,0.997-0.982,1.743-1.95,1.735l-30.047-0.182c-0.141-0.004-0.28,0.066-0.375,0.186c-0.094,0.118-0.142,0.273-0.116,0.413
				c2.068,11.762,13.149,13.718,15.739,14.062c3.146,0.427,11.645,1.258,18.699-5.78c4.832-4.81,7.071-9.829,7.429-21.025
				c0.353-10.708-8.132-28.569-31.131-28.653c-12.009-0.044-31.643,10.088-33.57,33.223C16.083,66.766,28.553,84.02,48.54,87.51
				c27.377,4.776,38.256-7.408,38.256-7.408c0.507-0.308,1.099-0.148,1.333,0.354l4.324,8.51c0.108,0.229,0.194,0.541,0.108,0.824
				c-0.093,0.273-0.172,0.361-0.468,0.604C92.101,90.392,76.226,105.138,44.933,98.093z M42.286,39.479
				c-0.085,0.199-0.124,0.412-0.046,0.593c0.079,0.177,0.25,0.29,0.438,0.29l19.159,0.143c0.147,0.004,0.297-0.066,0.405-0.193
				c0.102-0.129,0.146-0.291,0.125-0.442c-0.296-1.804-0.866-3.609-1.965-5.027c-1.515-1.965-4.542-3.288-7.953-3.187
				c-3.004,0.09-5.087,1.144-7.031,2.97C43.801,36.147,42.614,38.669,42.286,39.479z" />

		<path id="shape-facebook" d="M94.48,0H5.519C2.471,0,0,2.471,0,5.52v88.96c0,3.049,2.47,5.521,5.519,5.521h63.479L53.411,100V61.273h-13.03V46.181
					h13.03v-11.13c0-12.916,7.89-19.949,19.412-19.949c5.52,0,10.263,0.41,11.645,0.595v13.497l-7.991,0.004
					c-6.265,0-7.479,2.979-7.479,7.348v9.636h14.945l-1.947,15.093H68.998v38.726H94.48c3.047,0,5.52-2.472,5.52-5.521V5.52
					C100,2.471,97.529,0,94.48,0z" />

		<path id="shape-github" d="M50.049,0.3c14.18,0.332,25.969,5.307,35.366,14.923S99.675,36.9,100,51.409c-0.195,11.445-3.415,21.494-9.658,30.146
				c-6.244,8.65-14.406,14.772-24.488,18.366c-1.234,0.199-2.112,0.017-2.633-0.549c-0.521-0.565-0.781-1.215-0.781-1.946
				l0.098-14.074c0-2.396-0.342-4.377-1.023-5.939c-0.684-1.564-1.479-2.745-2.391-3.544c5.788-0.399,10.977-2.312,15.561-5.739
				c4.586-3.428,7.01-9.934,7.27-19.517c0-2.795-0.455-5.324-1.366-7.586c-0.91-2.263-2.179-4.293-3.805-6.09
				c0.391-0.665,0.716-2.196,0.976-4.592c0.262-2.396-0.229-5.391-1.463-8.983c0-0.2-1.105-0.15-3.316,0.149
				c-2.211,0.299-5.691,2.013-10.439,5.141c-4.032-1.131-8.18-1.697-12.438-1.697c-4.26,0-8.439,0.566-12.537,1.697
				c-4.748-3.128-8.228-4.841-10.439-5.141c-2.211-0.3-3.317-0.35-3.317-0.149c-1.235,3.593-1.723,6.588-1.463,8.983
				c0.26,2.396,0.585,3.927,0.976,4.592c-1.626,1.73-2.894,3.743-3.805,6.039c-0.911,2.296-1.366,4.842-1.366,7.637
				c0.26,9.518,2.667,16.006,7.219,19.466c4.553,3.461,9.724,5.391,15.512,5.79c-0.715,0.599-1.366,1.464-1.951,2.596
				s-1.008,2.528-1.268,4.192c-1.561,0.865-3.772,1.281-6.634,1.248c-2.862-0.033-5.496-1.881-7.902-5.54
				c0-0.2-0.65-1.032-1.951-2.496c-1.301-1.464-3.187-2.396-5.659-2.795c-0.325-0.066-0.976,0.05-1.951,0.35
				c-0.976,0.3-0.456,1.214,1.561,2.745c0.065-0.066,0.699,0.449,1.902,1.547c1.203,1.1,2.423,3.078,3.659,5.939
				c-0.195,0.466,0.862,1.863,3.17,4.191c2.309,2.329,6.846,2.93,13.61,1.797l0.098,9.483c0,0.731-0.26,1.381-0.781,1.946
				c-0.52,0.564-1.398,0.748-2.634,0.549c-10.082-3.594-18.244-9.716-24.488-18.367C3.415,72.604,0.195,62.556,0,51.11
				C0.325,36.602,5.187,24.54,14.585,14.923C23.984,5.308,35.772,0.333,49.951,0L50.049,0.3z" />

		<g id="shape-googleplus">
			<path id="googleplus-1" d="M44.438,59.102c-0.699-0.091-1.137-0.091-2.012-0.091c-0.789,0-5.518,0.177-9.193,1.441
					c-1.927,0.714-7.527,2.868-7.527,9.237c0,6.375,6.041,10.957,15.408,10.957c8.406,0,12.873-4.139,12.873-9.694
					C53.984,66.364,51.096,63.945,44.438,59.102z" />
			<path id="googleplus-2" d="M49.167,35.495c0-6.463-3.768-16.514-11.029-16.514c-2.283,0-4.729,1.168-6.133,2.965c-1.49,1.887-1.923,4.301-1.923,6.643
					c0,6.01,3.412,15.971,10.944,15.971c2.188,0,4.543-1.07,5.953-2.509C48.991,39.979,49.167,37.112,49.167,35.495z" />
			<path id="googleplus-3" d="M96.879,0H3.121C1.396,0,0,1.428,0,3.19v93.615c0,1.766,1.396,3.193,3.121,3.193h93.758c1.727,0,3.121-1.43,3.121-3.193
					V3.19C100,1.428,98.604,0,96.879,0z M36.819,84.409c-12.697,0-18.821-6.188-18.821-12.836c0-3.229,1.572-7.804,6.739-10.945
					c5.425-3.41,12.784-3.856,16.726-4.135c-1.231-1.614-2.628-3.318-2.628-6.1c0-1.523,0.438-2.422,0.872-3.5
					c-0.966,0.092-1.923,0.18-2.8,0.18c-9.277,0-14.53-7.095-14.53-14.094c0-4.123,1.836-8.703,5.6-12.025
					c4.994-4.217,11.815-5.361,16.549-5.361h17.25l-5.692,3.659h-5.426c2.013,1.702,6.211,5.293,6.211,12.116
					c0,6.638-3.675,9.787-7.348,12.746c-1.141,1.164-2.455,2.42-2.455,4.398c0,1.969,1.314,3.044,2.275,3.859l3.155,2.507
					c3.853,3.319,7.354,6.371,7.353,12.569C59.848,75.887,51.877,84.409,36.819,84.409z M83.404,48.903h-8.801v9.027H70.27v-9.027
					h-8.758v-4.48h8.758v-8.975h4.334v8.975h8.801V48.903z" />
		</g>

		<path id="shape-twitter" d="M100.001,17.942c-3.681,1.688-7.633,2.826-11.783,3.339
				c4.236-2.624,7.49-6.779,9.021-11.73c-3.965,2.432-8.354,4.193-13.026,5.146C80.47,10.575,75.138,8,69.234,8
				c-11.33,0-20.518,9.494-20.518,21.205c0,1.662,0.183,3.281,0.533,4.833c-17.052-0.884-32.168-9.326-42.288-22.155
				c-1.767,3.133-2.778,6.773-2.778,10.659c0,7.357,3.622,13.849,9.127,17.65c-3.363-0.109-6.525-1.064-9.293-2.651
				c-0.002,0.089-0.002,0.178-0.002,0.268c0,10.272,7.072,18.845,16.458,20.793c-1.721,0.484-3.534,0.744-5.405,0.744
				c-1.322,0-2.606-0.134-3.859-0.379c2.609,8.424,10.187,14.555,19.166,14.726c-7.021,5.688-15.867,9.077-25.48,9.077
				c-1.656,0-3.289-0.102-4.895-0.297C9.08,88.491,19.865,92,31.449,92c37.737,0,58.374-32.312,58.374-60.336
				c0-0.92-0.02-1.834-0.059-2.743C93.771,25.929,97.251,22.195,100.001,17.942L100.001,17.942z" />

		<path id="shape-youtube" d="M98.77,27.492c-1.225-5.064-5.576-8.799-10.811-9.354C75.561,16.818,63.01,15.993,50.514,16
				c-12.495-0.007-25.045,0.816-37.446,2.139c-5.235,0.557-9.583,4.289-10.806,9.354C0.522,34.704,0.5,42.574,0.5,50.001
				c0,7.426,0,15.296,1.741,22.509c1.224,5.061,5.572,8.799,10.807,9.352c12.399,1.32,24.949,2.145,37.446,2.14
				c12.494,0.005,25.047-0.817,37.443-2.14c5.234-0.555,9.586-4.291,10.81-9.352c1.741-7.213,1.753-15.083,1.753-22.509
				S100.51,34.704,98.77,27.492 M67.549,52.203L43.977,64.391c-2.344,1.213-4.262,0.119-4.262-2.428V38.036
				c0-2.548,1.917-3.644,4.262-2.429l23.572,12.188C69.896,49.008,69.896,50.992,67.549,52.203" />

		<path id="shape-codepen" d="M99.961,34.205c-0.009-0.063-0.025-0.124-0.035-0.187c-0.021-0.121-0.043-0.242-0.075-0.36
    c-0.019-0.071-0.044-0.14-0.067-0.208c-0.034-0.105-0.068-0.21-0.11-0.312c-0.029-0.071-0.063-0.142-0.096-0.21
    c-0.045-0.097-0.092-0.192-0.146-0.284c-0.04-0.068-0.082-0.134-0.122-0.2c-0.058-0.089-0.117-0.176-0.182-0.26
    c-0.047-0.063-0.097-0.126-0.147-0.187c-0.068-0.079-0.139-0.158-0.214-0.232c-0.056-0.058-0.112-0.115-0.171-0.168
    c-0.079-0.071-0.161-0.14-0.243-0.205c-0.064-0.05-0.128-0.1-0.195-0.147c-0.025-0.016-0.047-0.037-0.071-0.053L52.383,0.722
    c-1.444-0.962-3.323-0.962-4.767,0L1.914,31.19c-0.024,0.016-0.046,0.037-0.071,0.053c-0.067,0.047-0.13,0.098-0.193,0.147
    c-0.084,0.065-0.166,0.134-0.243,0.205c-0.061,0.053-0.116,0.11-0.172,0.168c-0.075,0.074-0.146,0.153-0.213,0.232
    c-0.051,0.061-0.101,0.124-0.148,0.187c-0.063,0.084-0.124,0.171-0.18,0.26c-0.043,0.066-0.084,0.132-0.124,0.2
    c-0.053,0.092-0.1,0.187-0.146,0.284c-0.033,0.068-0.067,0.139-0.096,0.21c-0.042,0.103-0.076,0.208-0.11,0.312
    c-0.022,0.068-0.047,0.137-0.067,0.208c-0.031,0.119-0.052,0.239-0.075,0.36c-0.011,0.063-0.026,0.124-0.034,0.187
    C0.014,34.389,0,34.576,0,34.765v30.468c0,0.189,0.014,0.376,0.04,0.563c0.008,0.061,0.023,0.124,0.034,0.184
    c0.022,0.121,0.043,0.242,0.075,0.36c0.02,0.071,0.045,0.14,0.067,0.208c0.034,0.105,0.068,0.21,0.11,0.315
    c0.029,0.071,0.063,0.14,0.096,0.208c0.046,0.097,0.094,0.191,0.146,0.287c0.039,0.066,0.08,0.131,0.124,0.197
    c0.056,0.089,0.117,0.176,0.18,0.261c0.047,0.065,0.097,0.126,0.148,0.187c0.067,0.079,0.138,0.158,0.213,0.231
    c0.057,0.058,0.112,0.115,0.172,0.168c0.078,0.071,0.159,0.14,0.243,0.206c0.063,0.05,0.126,0.1,0.193,0.147
    c0.025,0.016,0.047,0.037,0.071,0.053l45.703,30.469C48.338,99.758,49.169,100,50,100c0.83,0,1.661-0.242,2.383-0.723
    l45.703-30.469c0.024-0.016,0.045-0.037,0.071-0.053c0.067-0.047,0.13-0.097,0.195-0.147c0.083-0.066,0.164-0.134,0.243-0.206
    c0.059-0.053,0.115-0.11,0.171-0.168c0.075-0.074,0.146-0.153,0.214-0.231c0.05-0.061,0.1-0.121,0.147-0.187
    c0.065-0.084,0.124-0.171,0.182-0.261c0.041-0.066,0.083-0.131,0.122-0.197c0.054-0.095,0.101-0.189,0.146-0.287
    c0.033-0.068,0.067-0.137,0.096-0.208c0.042-0.105,0.076-0.21,0.11-0.315c0.022-0.068,0.048-0.137,0.067-0.208
    c0.032-0.118,0.054-0.239,0.075-0.36c0.01-0.061,0.026-0.124,0.035-0.184C99.985,65.61,100,65.423,100,65.233V34.765
    C100,34.576,99.985,34.389,99.961,34.205z M54.297,12.327l33.668,22.444L72.927,44.831L54.297,32.369V12.327z M45.703,12.327
    v20.042L27.074,44.831l-15.04-10.061L45.703,12.327z M8.594,42.809L19.345,50L8.594,57.19V42.809z M45.703,87.672L12.035,65.228
    l15.04-10.058L45.703,67.63V87.672z M50,60.165L34.802,50L50,39.833L65.198,50L50,60.165z M54.297,87.672V67.63L72.927,55.17
    l15.039,10.058L54.297,87.672z M91.405,57.19L80.656,50l10.75-7.191V57.19z" />
	</defs>
</svg><!-- .social-drawing -->

<!-- Scripts -->
<?php
// jQuery lib
$scripts = file_get_contents( 'http://code.jquery.com/jquery.min.js' );

// scrollspy
$scripts .= "\n" . file_get_contents( $path . '/js/scrollspy.min.js' );

// resume js
$scripts .= "\n" . minify_js( file_get_contents( $path . '/js/script.js' ) );
?>
<script><?php echo $scripts; ?></script>
</body>
</html>
<?php
// output flush
if ( $file_output )
{
	// get flush & clean/clear
	$content = ob_get_clean();

	// save content
	file_put_contents( 'index.html', $content );

	// redirect to result file
	header( 'location: index.html' );
	die();
}