+(function() {
	class Swipe extends React.Component{
		render(){
			console.log(this.props.poster);

			let imgnodes = this.props.poster.map(function(img){
				return <div className='swipe_img'><img src={Clips.staticUrl(img)} /></div>
			});

		return(
				<ReactSwipe continuous={false}>
					{imgnodes}
				</ReactSwipe>
			)
		}
	}

	provides([Swipe],'widgets',true );
})()

