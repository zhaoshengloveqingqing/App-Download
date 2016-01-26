class Swiper extends React.Component{

  componentDidMount(){
    var mySwiper = new Swiper ('.swiper-container', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        slidesPerView: 2,
    })
  }

  render(){

    return(
      <div className='swiper-container' >
        <div className='swiper-wrapper'>
            <div className='swiper-slide'>Slide 1</div>
            <div className='swiper-slide'>Slide 2</div>
            <div className='swiper-slide'>Slide 3</div>
        </div>
      </div>
    )
  }
}

provides([Swiper],'widgets',true);
