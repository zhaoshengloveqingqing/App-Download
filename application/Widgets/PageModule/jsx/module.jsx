class TopContent extends React.Component{
	render(){
		return(
			<div className="top_title">
				<a href='javascript:window.history.back()'><i></i>{this.props.title}</a>
			</div>
		)
	}
}

class BottomContent extends React.Component{
	render(){
        let href = Clips.staticUrl(this.props.href || '#');
        href = this.props.dl || href;
        if(this.props.dl){
            href = href + '/' + this.props.id + '/' + this.props.filename;
        }
		return(
			<div className="bottom_content">
				<a href={href}>下载</a>
			</div>
		)
	}
}

provides([TopContent,BottomContent],'widgets',true);

