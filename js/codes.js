/*Codes*/

class Codes {

  //1. Create object
  constructor(){
    this.codeInput = '';
    this.events();
  }

  //2. Define events
  events(){
    jQuery(document).on("keydown", this.keyPressDispatcher.bind(this));
  }

  //3. Define methods
keyPressDispatcher(e){
  if (!jQuery("input, textarea").is(':focus')){
    this.codeInput += e.keyCode.toString();
    let konami = '38384040373937396665';
    if (this.codeInput.includes(konami)){
        jQuery('.glitch-img').mgGlitch({
          destroy: false,
          glitch: true,
          scale: true,
          blend: true,
          blendModeType: 'hue',
          glitch1TimeMin: 200,
          glitch1TimeMax: 400,
          glitch2TimeMin: 10,
          glitch2TimeMax: 100
        });
      this.codeInput = '';
    }
  }
}

};

var codes = new Codes();

export default Codes;
