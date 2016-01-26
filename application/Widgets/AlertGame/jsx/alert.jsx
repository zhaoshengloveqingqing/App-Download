class AlertGame extends React.Component{
    componentDidMount() {
        if(this.props.list){
            $('.alertgame').fadeIn();
        }
    }

    close(){
        $('.alertgame').fadeOut();
    }

    render(){
        let list = this.props.list || [];
        return(
          <div className='alertgame' >
              <section>
                <div className='alert_header_info'>
                  <span className='rightText' onClick={this.close}> 关闭 </span>
                </div>
                <p className='content'>
                   <img src={Clips.staticUrl('application/static/img/warning.png')}/>
                   请先下载APP后，再断续访问！感谢您的支持
                 </p>
              </section>
              <setion>
                  <div className='alert_header_info'>
                    <span className='leftText'>热门游戏下载</span>
                    <span className='rightText'><a href={Clips.staticUrl('app/all/top')}>更多 > </a> </span>
                  </div>
                  <widgets.Gamelistforhot list={list} />
              </setion>
          </div>
        )
    }
}

provides([AlertGame],'widgets', true);
