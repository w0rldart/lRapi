<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Laravel: A Framework For Web Artisans</title>
	<meta name="viewport" content="width=device-width">
	{{ HTML::style('laravel/css/style.css') }}
	{{ HTML::style('css/w0rldart.css') }}
</head>
<body>

	<div class="wrapper">		
		
		<header>
			<h1>lRapi</h1>
			<h2>Laravel REST Api</h2>

			<p class="intro-text" style="margin-top: 45px;"></p>
		</header>

		<div role="main" class="main">

			<div class="home">

				<section>
					<header>
						<h2>What is it?</h2>
					</header>					
					<p> <strong>lRapi</strong> is just a demo/example of a REST API Service based on Laravel framework, that I've made to provide an
						API service for a Android (and iOS by someone else) app that I was developing.
					</p>
					<p> Personaly, I think this is a good starting point, but needs to be adapted to each one's needs. </p>
				</section>

				<section>
					<header>
						<h2>Structure</h2>
					</header>

					<div class="details">
						<h3>Config</h3>
						<ul class="listing">
							<li> <i>config/lrapi_config.php</i> -> Custom declared parameters for the app </li>
							<li> <i>config/lrapi_status.php</i> -> Status codes for the responses </li>
						</ul>
						<h3>Controllers</h3>
						<ul class="listing">
							<li> <i>controllers/api.php</i> -> The main controller which is extended by the others within the api/ fodler </li>
							<li> <i>controllers/activate.php</i> -> Controller that contains the method which user will access to activate its account </li>
							<li> <i>controllers/<strong>api/</strong>devices.php</i> -> Controller that will handle the devices </li>
							<li> <i>controllers/<strong>api/</strong>users.php</i> -> Controller that will handle the users </li>
							<li> <i>controllers/<strong>api/</strong>users.php</i> -> Controller that will handle the places </li>
						</ul>
					</div>
				</section>

				<section>
					<header>
						<h2>Response</h2>
						<p>All the responses are in JSON format</p>
					</header>
				</section>

				<section>
					<header>
						<h2>Requests</h2>

						<div class="details">
							<h3>URL Structure</h3>
							<pre>http://site.com/<strong>api</strong>/<strong>request</strong>/<strong>(method)</strong></pre>
							<table class="params">
								<tbody>
									<tr>
										<td class="param">request</td>
										<td>Default controllers: <strong>devices/</strong>, <strong>users/</strong>, <strong>places/</strong></td>
									</tr>
									<tr>
										<td class="param">method</td>
										<td>
											Methods are not always required, because of the
											<code><a href="http://laravel.com/docs/controllers#restful-controllers" target="_blank">public $restful = true;</a></code>
										</td>
									</tr>
								</tbody>
							</table>

							<h3>Requests</h3>

							<p> All methods must include a <strong>hash</strong> parameter, which is generated using HMAC SHA1 using the key provided when registering the device </p>

							<table class="params">
								<thead>
									<tr>
										<th width="15%">Name</th>
										<th>Methods</th>
										<th width="15%">Parameters</th>
										<th>Returns</th>
										<th>Description</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td class="param">api/device [POST]</td>
										<td> / </td>
										<td> device_id </td>
										<td> key, token </td>
										<td> This is the first thing to be called, as it will register the device in the database and return the key and token later to be used </td>
									</tr>
									<tr>
										<td class="param">api/user [POST]</td>
										<td> / </td>
										<td>
											<ul class="listing" style="margin:0; padding:0;">
												<li> device_id </li>
												<li> email </li>
												<li> name (optional) </li>
												<li> (*) password </li>
												<li> (*) token_facebook </li>
											</ul>
										</td>
										<td> An error with its proper status code, or the user_id if successful </td>
										<td> Use it to add an user </td>
									</tr>
									<tr>
										<td>  </td>
										<td> /login </td>
										<td>
											<ul class="listing" style="margin:0; padding:0;">
												<li> email </li>
												<li> (*) password </li>
												<li> (*) token_facebook </li>
											</ul>
										</td>
										<td> An error message or a 200 response </td>
										<td> Method to authenticate user </td>
									</tr>
									<tr>
										<td>  </td>
										<td> /logout </td>
										<td>
											<ul class="listing" style="margin:0; padding:0;">
												<li> device_id </li>
											</ul>
										</td>
										<td> An error message or a 200 response </td>
										<td> Method regenerate credentials for the user's device </td>
									</tr>
									<tr>
										<td>  </td>
										<td> /validate </td>
										<td>
											<ul class="listing" style="margin:0; padding:0;">
												<li> user_id </li>
											</ul>
										</td>
										<td> An error message or a 200 response </td>
										<td> Method check if user is validated </td>
									</tr>
									<tr>
										<td class="param">api/place [GET]</td>
										<td> /data/{id} </td>
										<td>  </td>
										<td>  </td>
										<td>  </td>
									</tr>
									<tr>
										<td class="param">api/place [POST]</td>
										<td> /check </td>
										<td>  </td>
										<td>  </td>
										<td>  </td>
									</tr>
								</tbody>
							</table>
						</div>
					</header>
				</section>

				<section>
					<header>
						<h2>Workflow</h2>
						<p> The app has been designed to be used the following way </p>
						<ol>
							<li> Register the device at api/device </li>
							<li> Save the <strong>key</strong> and <strong>token</strong>, you will use them later </li>
							<li> Any <strong>api/{}</strong> calls, will have to contain the token which will be used to authenticate the calls and the key to generate the hash for the parameters sent </li>
							<li> iOS HMAC SHA1 function to generate the hash (thanks to <a href="htt://twitter.com/nandodelauni" target="_blank">@nandodelauni</a>) <br/>
								<code>
- (NSString *)hmacSha1WithSecret:(NSString *)key
{
	const char *cKey  = [key cStringUsingEncoding:NSUTF8StringEncoding];
	const char *cData = [self cStringUsingEncoding:NSUTF8StringEncoding];

	unsigned char cHMAC[CC_SHA1_DIGEST_LENGTH];

	CCHmac(kCCHmacAlgSHA1, cKey, strlen(cKey), cData, strlen(cData), cHMAC);
	NSData *HMAC = [[NSData alloc] initWithBytes:cHMAC length:sizeof(cHMAC)];

	NSString *hash = [HMAC base64EncodedString];								    

	return hash;
}
								</code>
							</li>
						</ol>
					</header>
				</section>
				
				<section>
					<header>
						<h2>Vhost</h2>
					</header>

					<p> Vhost configuration demo, for apache </p>

					<code>
&#60;VirtualHost *:80&#62;
	DocumentRoot /var/www/app.com/web
	ServerName app.com

	ErrorLog /var/www/logs/app-error.log
	LogLevel debug

	&#60;Directory "/var/www/app.com/web"&#62;
			Options Indexes Includes FollowSymLinks MultiViews
			AllowOverride all
			Order allow,deny
			Allow from all
	&#60;/Directory&#62;
&#60;/VirtualHost&#62;
					</code>
				</section>
				
				<section>
					<header>
						<h2>More info</h2>
					</header>

					<p> You may find the logs demo database with the lRapi table here: <a href="https://gist.github.com/w0rldart/5191274">https://gist.github.com/w0rldart/5191274</a> </p>
					<p> Well, I still have to complete the documentation and add it to my blog as well. Mean while you may find me here: </p>

					<ul class="out-links">
						<li><a href="http://twitter.com/w0rldart">Twitter</a></li>
						<li><a href="http://w0rldart.com">Personal Page</a></li>
						<li><a href="http://github.com/w0rldart">GitHub</a></li>
					</ul>
				</section>

			</div>
		</div>
	</div>
</body>
</html>
