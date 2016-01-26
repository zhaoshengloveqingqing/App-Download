React.render(
	(<div>
        <div>
            <widgets.Header/>
        </div>
	      <div className="menu">
		      <widgets.Navlist actived={datastore.get('page_tab')}/>
	      </div>
        <div>
            <widgets.Advertlist/>
        </div>
        <div>
          <widgets.GameListHeader text={datastore.get('lastMonthName')} moretext='显示全部' photo='application/static/img/more_icon.png' href='app/all/last'/>
          <widgets.Gamelist list={datastore.get('lastMonthList')} />
        </div>
        <div>
          <widgets.GameListHeader text='最新发布游戏' moretext='显示全部' photo='application/static/img/more_icon.png' href='app/all/latest' />
          <widgets.Gamelist list={datastore.get('latestList')}/>
        </div>
        <div>
          <widgets.GameListHeader text='随机刷游戏' moretext='刷一下' board='任性，想刷就刷' photo='application/static/img/refresh_icon.png' refresh="refresh"/>
          <widgets.Gamelist list={datastore.get('randList')} refresh='refreshdom' />
        </div>
        <div>
            <widgets.GameListHeader text='最高人气游戏' moretext='显示全部'  photo='application/static/img/more_icon.png' href='app/all/top'/>
            <widgets.Gamelistforhot list={datastore.get('hottestList')}/>
        </div>
    </div>),
	document.getElementById('home_main'));
