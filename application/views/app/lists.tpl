{extends file="base-layout.tpl"}
{block name="main"}
	{jsx}
	{literal}
		<div>
			<div>
				<widgets.TopContent title={datastore.get('category_name')} />
				<widgets.Applist applist={datastore.get('lists')}/>
			</div>
		</div>
	{/literal}
	{/jsx}
{/block}
