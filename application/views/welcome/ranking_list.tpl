{extends file="base-layout.tpl"}
{block name="main"}
	{jsx}
		{literal}
			<div>
				<div>
					<widgets.Header/>
				</div>
				<div className="menu">
					<widgets.Navlist actived={datastore.get('page_tab')}/>
				</div>
				<div className="ranking">
						<widgets.Ranklist actived={datastore.get('rank_tab')}/>
				</div>
				<div className="banner">
					<ReactSwipe continuous={false}>
						<div className="banner_img"><img src= {Clips.staticUrl('application/static/img/apps/paodanniao/tuiguang.jpg')} /></div>
						<div className="banner_img"><img src= {Clips.staticUrl('application/static/img/apps/leishenzhanji2015/tuiguang.jpg')} /></div>
						<div className="banner_img"><img src= {Clips.staticUrl('application/static/img/apps/chengshifeiche/tuiguang.jpg')} /></div>
					</ReactSwipe>
				</div>
				<div className="ranking-list">
					<widgets.Applist applist={datastore.get('lists')}/>
				</div>
			</div>
		{/literal}
	{/jsx}
{/block}
