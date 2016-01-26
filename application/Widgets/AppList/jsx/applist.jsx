class Applistone extends React.Component {
	render(){
		let icon = Clips.staticUrl(this.props.icon);
		let href  = this.props._href || this.props.href;
		return(
			<li>
				<div className="game">
					<a href={href}><img src={icon}/></a>
					<div className="gameinfo">
						<h4><a href={href}>{this.props.title}</a></h4>
						<p>
							<span>{this.props.type}</span>
							<span className="number">{this.props.number}</span>人下载
							<span className="size">{this.props.size}</span>
						</p>
						<p className="belong">{this.props.company}</p>
					</div>
				</div>
                <a className="down" href={this.props.dl}>下载</a>
			</li>
		)
	}
}

class Applistonetest extends React.Component{
		render(){
				let detail = this.props.detail;
				if(this.props._href) {
					var _href = '#';
				}
				return(
					<div>
							<Applistone {...detail}  _href={_href}/>
					</div>
				)
		}
}

class Download extends React.Component{
    render(){
		let href = Clips.staticUrl(this.props.href || '#');
        return(
            <a className="down" href={href}>下载</a>
        )
    }
}


class Applist extends React.Component {
	constructor(){
			super();
			this.state = {
                applist: [],
                requestTime: 1,
            };
	}

    loadGames(){

        let page = this;

         $.get(Clips.staticUrl('app/more/'+this.state.requestTime),(res) => {
            this.loading = false;
            if (res.length === 0) {
              this.doRequest  = false;
              $('.loadingimg').hide();
            }

            let requestTime = page.state.requestTime + 1;
            let applist = page.state.applist.concat(res);

            page.setState({
                applist: applist,
                requestTime : requestTime
            })
          })
      }

    loadNextGames(){
                if(this.loading)
                    return;
				let isPageBottom =  $(window).scrollTop() + $(window).height() > ($(document).height() - 100);
                console.log($(window).scrollTop() + ' ' +$(window).height() +' '+$(document).height());

				if (!isPageBottom) {
							return;
				}

                this.loading = true;
                if (this.doRequest) {
				    this.loadGames();
                }
    }

    componentDidMount(){
        this.loadGames();
        var didLoad = false;
        let page = this;
        this.doRequest = true;

		$('html').css({'overflow':'scroll','height':'auto'});

        events.on(window, 'scroll', function(){
             didLoad = true;
        });

        let setIntervalGame = setInterval(function(){

            if(didLoad){
                didLoad = false;
            }else{
                return;
            }
            page.loadNextGames();

        },200);
    }

    componentWillUnmount(){
        clearInterval(setIntervalGame);
    }

	render(){

		let applist = this.state.applist || [];
        let dl = datastore.get('dl');
        let loading = Clips.staticUrl('application/static/img/loading.gif');
		let listresult = Array.prototype.map.call(applist,function(list){
            let url = '#';
            if(dl !== '')
                url = dl + '/' + list.id + '/' + list.filename;
			return <Applistone {...list} dl={url}/>
		});

        let loadingstyle={
            wrap:{
                textAlign: 'center',
            },
            image:{
                width: '65px',
                height: '65px',
                margin: '0 auto',
            }
        };
		return(
			<ul className="list">
				{listresult}
                <li style={loadingstyle.wrap} className='loadingimg' ><img style={loadingstyle.image} src={loading}/> </li>
			</ul>
		)
	}
}

class Gamedetail extends React.Component{
	render(){
		let detail = this.props.detail || {};
		return(
			<div>
				<div className='update_time'>更新时间<span>{detail.timestamp}</span></div>
				<p className='detail-content'>
					{detail.content}
				</p>
			</div>
		)
	}
}


provides([Applistone,Applist,Applistonetest,Gamedetail],"widgets", true);
