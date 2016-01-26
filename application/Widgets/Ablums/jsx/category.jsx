class Album extends React.Component{
  render(){
    let imgurl = Clips.staticUrl(this.props.icon);

    return(
      <div className='album' >
        <a href={this.props.href}><img src={imgurl}/></a>
        <div className='lableTitle'>
          {this.props.name}
        </div>
      </div>
    )
  }
}

class Albumlist extends React.Component{

  render(){
    let list = this.props.list || [];
    let listNodes = Array.prototype.map.call(list ,function(group){
        return(
          <Album {...group}/>
        )
    });
    return(
      <div className='albumlist'>
        {listNodes}
      </div>
    )
  }
}

class Categorysearch extends React.Component{
	handleClick(event){

		if($('.search input')[0].value === ''){
			alert('请输入名称');
			return;
		}

		$('.search form')[0].submit();
	}

	render(){
		return(
				<div className='header_info'>
					<a href='javascript:window.history.back()'><i></i>游戏分类</a>

					<div className='search'>
					<form action={Clips.staticUrl('app/search')} method="post" id="category_form">
						<input name="app_name" placeholder=''/>
						<span onClick={this.handleClick}><input className="submit" type='button' /></span>
					</form>
					</div>
					<div className='helper'>
						<a href={Clips.staticUrl('app/about')}> 关于</a>
					</div>
				</div>
		)
	}
}
provides([Album,Albumlist,Categorysearch], 'widgets' , true);
