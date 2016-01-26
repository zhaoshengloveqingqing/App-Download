{extends file="base-layout.tpl"}
{block name="main"}
	{jsx}
	{literal}
		<div>
			<widgets.TopContent title="意见反馈"/>
			<div className="feedback">
				<form>
					<textarea placeholder='输入你的意见...'>

					</textarea>
					<div className="action">
						<a href="#">意见反馈</a>
					</div>
				</form>
			</div>
		</div>
	{/literal}
	{/jsx}
{/block}
