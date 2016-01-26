{extends file="base-layout.tpl"}
{block name='main'}
    {jsx}
        {literal}
            <div>
				<div>
					<widgets.TopContent title='详情'/>
				</div>
				<div className="appinfo_detail">
					<widgets.Applistonetest detail={datastore.get('app')} _href="disable"/>
				</div>
				<div  className="appinfo_detail_img">
					<widgets.Swipe continuous={false} poster={datastore.get('app').poster} />
				</div>
				<div className='content'>
					<h3> 内容提要 </h3>
					<widgets.Gamedetail detail={datastore.get('app')} />
				</div>
	            <div className="relation content">
		            <h3> 相关推荐 </h3>
		            <widgets.Gamelistdetail list={datastore.get('sames')} dl={datastore.get('dl')}/>
	            </div>

				<div>
					<widgets.BottomContent keywords={datastore.get('app').online_time_action} dl={datastore.get('dl')} id={datastore.get('app').id} filename={datastore.get('app').filename}/>
				</div>
            </div>
        {/literal}
    {/jsx}
{/block}
