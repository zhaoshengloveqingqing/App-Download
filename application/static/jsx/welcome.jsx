React.render(
		(<div className="notice-wrap">
			<div className="welcome">
				<div className="welcome-info">
					<h1>派尔商业Wi-Fi</h1>
					<p>
						派尔商业Wi-Fi，您新一代的Wi-Fi应用、Wi-Fi营销解决方案。 派尔商业Wi-Fi，即覆盖了传统的Wi-Fi共享模式，
						而且从根本上改变客户共享您Wi-Fi资源的方式，让您的Wi-Fi成为您主动营销的工具<br/>
					</p>
					<a href={Clips.staticUrl('/')}>现在开始</a>
				</div>
			</div>
			<widgets.Logo />
			<widgets.Copyright />
		</div>), 
document.getElementById('welcome_main'));
