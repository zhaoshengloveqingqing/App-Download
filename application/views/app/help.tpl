{extends file="base-layout.tpl"}
{block name="main"}
	{jsx}
	{literal}
		<div>
			<widgets.TopContent title="帮助 & 反馈"/>
			<div className="help-content">
				<div className="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div className="panel panel-default" id="default">
						<div className="panel-heading" role="tab" id="headingOne">
							<h4 className="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="trues" aria-controls="collapseOne">
									1.如何下载/安装应用？
									<i id="glyphicon-menu-down"></i>
								</a>
							</h4>
						</div>
						<div id="collapseOne" className="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div className="panel-body">
								<p>①下载：选择您喜欢的应用, 点击页面上“下载”按钮
									即可下载；在“管理”-“下载管理”中查看正在下载
									和已下载的应用；
								</p>
								<p>②安装：下载完成后，下载按钮会变成“安装”按钮，
									点击即可安装；或者在“管理”-“下载管理”中，安
									装已下载的应用。
								</p>
							</div>
						</div>
					</div>
					<div className="panel panel-default" id="default">
						<div className="panel-heading" role="tab" id="headingTwo">
							<h4 className="panel-title">
								<a className="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									2.如何更新/卸载应用？
									<i id="glyphicon-menu-down"></i>
								</a>
							</h4>
						</div>
						<div id="collapseTwo" className="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
							<div className="panel-body">
								<p>
									卸载应用
								</p>
							</div>
						</div>
					</div>
					<div className="panel panel-default" id="default">
						<div className="panel-heading" role="tab" id="headingThree">
							<h4 className="panel-title">
								<a className="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									3.怎么连接电脑？
									<i id="glyphicon-menu-down"></i>
								</a>
							</h4>
						</div>
						<div id="collapseThree" className="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
							<div className="panel-body">
								<p>连接电脑</p>
							</div>
						</div>
					</div>
					<div className="panel panel-default" id="default">
						<div className="panel-heading" role="tab" id="headingFour">
							<h4 className="panel-title">
								<a className="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
									4.怎么进行照片备份？
									<i id="glyphicon-menu-down"></i>
								</a>
							</h4>
						</div>
						<div id="collapseFour" className="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
							<div className="panel-body">
								<p>进行照片备份</p>
							</div>
						</div>
					</div>
					<div className="panel panel-default" id="default">
						<div className="panel-heading" role="tab" id="headingFive">
							<h4 className="panel-title">
								<a className="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
									5.什么是“Root用户自动安装/卸载”？
									<i id="glyphicon-menu-down"></i>
								</a>
							</h4>
						</div>
						<div id="collapseFive" className="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
							<div className="panel-body">
								<p>Root用户自动安装/卸载</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	{/literal}
	{/jsx}
{/block}
