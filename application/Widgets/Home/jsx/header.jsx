class Header extends React.Component{
  handleClick(event){

    if($('.search input')[0].value === ''){
       alert('请输入名称');
       return;
    }

    $('.search form')[0].submit();
  }

  render(){
    let imgurl = Clips.staticUrl('application/static/img/app_logo.png');
    let searchurl = Clips.staticUrl('app/search');
    let helpUrl = Clips.staticUrl('app/about');
	let text = "关于";

	if(datastore.get('app_loc')) {
		helpUrl = 'javascript: window.history.go( -( history.length - 2 ) );';
		text = "返回";
	}
	else {
		helpUrl = Clips.staticUrl('app/about');
	}
    let home = Clips.staticUrl('/');
    return(
      <div className='header'>
        <div className='header_info'>
            <a href={home}><img width="102px" src={imgurl} /></a>
          <div className='search'>
            <form action={searchurl} method='post' >
              <input name="app_name" placeholder='' />
              <span onClick={this.handleClick} ><input className="submit" type='button'  /></span>
            </form>
          </div>
          <div className='helper'>
            <a onclick="history.go(-1);">返回</a>
          </div>
        </div>
      </div>
    )
  }
}

class Navlist extends React.Component{
  render(){
    let recommendUrl = Clips.staticUrl('/');
    let listUrl = Clips.staticUrl('welcome/ranking');
    let categoryList = Clips.staticUrl('welcome/category');
    let actived =this.props.actived;
    return(
      <nav className='meau-navbar'>
        <a href={recommendUrl} className={actived == 'tab_recommend'?'active':''}>推荐</a>
        <a href={listUrl} className={actived == 'tab_list'?'active':''}>榜单</a>
        <a href={categoryList} className={actived == 'tab_category'?'active':''}>分类</a>
      </nav>
    )
  }
}

class Ranklist extends React.Component{
  render(){
    let recommendUrl = Clips.staticUrl('welcome/ranking/total');
    let listUrl = Clips.staticUrl('welcome/ranking/month');
    let categoryList = Clips.staticUrl('welcome/ranking/week');
    let actived =this.props.actived;
    return(
      <nav className='ranking-btn'>
        <div className="category_group">
          <a href={recommendUrl} className={actived == 'total'?'active':''}>总榜</a>
          <a href={listUrl} className={actived == 'month'?'active':''}>月榜</a>
          <a href={categoryList} className={actived == 'week'?'active':''}>周榜</a>
        </div>
      </nav>
    )
  }
}


class Advert extends React.Component{
  render(){
    return(
      <div className='advert'>
        <span className="key_word">{this.props.keys}</span>
        <div className='right_word'>
          <span>{this.props.lineone}</span>
          <span>{this.props.linetwo}</span>
          <span>{this.props.linethree}</span>
        </div>
      </div>
    )
  }
}

class Advertlist extends React.Component{
  render(){
    let applist =this.props.applist || [
    {'keys':'抢','lineone':'独家首发','linetwo':'最新内容','linethree':'快人一步抢鲜看'},
    {'keys':'惠','lineone':'生活优惠','linetwo':'双11大促','linethree':'电商优惠券大集合'}
    ];
    let advertlist =Array.prototype.map.call(applist,function(list){
      return <Advert keys={list.keys} lineone={list.lineone} linetwo={list.linetwo} linethree={list.linethree}  />
    });

    return(
      <div className="advertment">
        {advertlist}
      </div>
    )
  }
}



provides([Header,Advert,Ranklist,Advertlist,Navlist],'widgets',true);
