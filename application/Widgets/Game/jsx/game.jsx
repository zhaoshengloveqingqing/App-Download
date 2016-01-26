class Gameone extends React.Component{
    render(){
      let  photo = Clips.staticUrl(this.props.icon);
      return(
          <a className="game_info" href={this.props.href}>
            <div className='gameone'>
                <img src={photo}/>
                <p>{this.props.name}</p>
            </div>
          </a>
      )
    }
}

class Gamelist extends React.Component{
    constructor(){
      super();
      this.state  = {list: []};
    }

    componentDidMount() {
      let list =  this.props.list || [];

      this.setState({list : list});

      let gamelist_wrap = document.querySelector('.gamelist_wrap');
      gamelist_wrap.addEventListener('touch', function(e){
          e.preventDefault();
      })

    }
    render(){
        let moreclass = 'game_list '+ this.props.refresh;
        let listNodes = Array.prototype.map.call(this.state.list,function(game,index){
            return <Gameone icon={game.icon} name={game.title} i={index} href={game.href}/>

        });
        return(
            <div className='gamelist_wrap'>
		        <div className={moreclass}>
					{listNodes}
                </div>
			</div>
        )
    }
}

class Gamelistdetail extends React.Component{
    render(){
      let list =  this.props.list || [];
        let dl = this.props.dl || '';
      let listNodes = Array.prototype.map.call(list,function(game){
          let icon = Clips.staticUrl(game.icon);
          let url = '#';
          if(dl !== '')
              url = dl + '/' + game.id + '/' + game.filename;
          return (
            <div className='gamelistdetailone'>
                <div className='gameone'>
                    <a href={game.href}><img src={icon}/></a>
					<a href={game.href}><p>{game.title}</p></a>
                </div>
              <p className="size">{game.size}</p>
              <a className="down" href={url}>下载</a>
            </div>
          )
      });

      return(
          <div className='gamelistdetail'>
                {listNodes}
          </div>
      )
    }
}

// 最高人气网游
class Gameonetwo extends React.Component{
    render(){
        let  icon = Clips.staticUrl(this.props.icon);
        return(
		        <div className='gameonetwo'>
					<a className='hot-img' href={this.props.href}><img src={icon}/></a>
					<div className='rightTable'>
						<h4><a href={this.props.href}>{this.props.name}</a></h4>
						<p>{this.props.size}</p>
						<a className='download' href={this.props.dl}>
						{this.props.downloadText} >>
						</a>
					</div>
				</div>
        )
    }
}

class Gamelistforhot extends React.Component{
    render(){
        let list =  this.props.list || [];
        let dl = datastore.get('dl');
        let listNodes = Array.prototype.map.call(list,function(game){
            let url = '#';
            if(dl !== '')
                url = dl + '/' + game.id + '/' + game.filename;
            return <Gameonetwo icon={game.icon} name={game.title} size={game.size} href={game.href} downloadText="下载" dl={url}/>
        });

        return(
            <div className='gamelistforhot'>
                {listNodes}
            </div>
        )
    }
}
class GameListHeader extends React.Component{
    refresh(){
        var that = this;
        $.get(Clips.staticUrl('app/rand'),function(res){
            if(that.props.refresh === 'refresh'){
                Array.prototype.map.call(res, function(li,index){
                    let img = Clips.staticUrl(li.icon);
                    $('.refreshdom p')[index].innerText = li.title;
                    $('.refreshdom  img')[index].src = img;
                    $('.refreshdom a')[index].href = li.href;
                })
            }
        })

    }

    render(){
    // 有refresh的添加class
    let moreclass = "more " + this.props.refresh;
		let  photo =  Clips.staticUrl(this.props.photo);
		let href = this.props.href ? Clips.staticUrl(this.props.href) : 'javascript:void(0)';
        console.log(href);
        return(
            <div className='gameListHeader'>
                <span className='text'>{this.props.text}</span>
                <span className='board'>{this.props.board}</span>
                <div className={moreclass} onClick={this.refresh.bind(this)}>
                    <span><a href={href}>{this.props.moretext}</a></span>
                    <span className="more_img"><img src={photo}/></span>
                </div>
            </div>
        )
    }
}



provides([Gameone,GameListHeader,Gamelist,Gamelistforhot,Gamelistdetail],"widgets", true);
