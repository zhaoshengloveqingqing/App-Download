{extends file="base-layout.tpl"}
{block name="main"}
	{jsx}
	{literal}
		<div>
			<widgets.TopContent title="关于"/>
			<widgets.Logo />
			<div className="about_content">
				<ul>
					<li><a href='#'>分享</a></li>
					<li><a href={Clips.staticUrl('app/help')}>帮助</a></li>
					<li><a href='#'>反馈</a></li>
					<li><a href={Clips.staticUrl('welcome/welcome')}>欢迎页</a></li>
					<li><a href={Clips.staticUrl('app/state')}>声明</a></li>
				</ul>
			</div>
			<div className="about-Copyright">
				<widgets.Copyright />
			</div>
		</div>
	{/literal}
	{/jsx}
{/block}
