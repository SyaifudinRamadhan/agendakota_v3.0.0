class MemeJS {
    constructor(props) {
        this.body = this.select("body")
        this.width = props.width
        this.height = props.height
        this.canvasDownloadLink = null

        this.init()
        this.canvas = this.select("canvas")
        this.ctx = this.canvas.getContext('2d')
    }
    select(dom) {
        return document.querySelector(dom)
    }
    createCanvas(w, h, ratio) {
        var can = document.createElement("canvas");
        can.setAttribute('id', 'generated_canvas');
        can.width = w * ratio;
        can.height = h * ratio;
        can.style.width = w + "px";
        can.style.height = h + "px";
        can.getContext("2d").setTransform(ratio, 0, 0, ratio, 0, 0);
        this.body.appendChild(can)
        return can;
    }
    init() {
        // this.body.style.textAlign = 'center'
        this.createCanvas(this.width, this.height, 1)
        this.canvasDownloadLink = document.createElement('a')
    }
    setTemplate(source) {
        this.addImage({
            src: source,
            width: this.width,
            height: this.height,
            position: {x:0,y:0}
        })
    }
    addText(props) {
        if(props.font === undefined) {
            this.ctx.font = "85px Arial";
        }else {
            this.ctx.font = props.font;
        }
        console.log(props.font);
        if (props.color !== undefined) {
            this.ctx.fillStyle = props.color;
        }
        setTimeout(() => {
            // this.ctx.fillText(props.text, props.position.x, props.position.y)
			this.ctx.textAlign = "center"
            // this.ctx.fillStyle = "#1e2142"
			this.ctx.fillText(props.text, props.position.x, props.position.y)
        }, 300);
    }
    addImage(props) {
        let img = new Image()
        img.src = props.src
        img.onload = () => {
            this.ctx.drawImage(img, props.position.x, props.position.y, props.width, props.height)
        }
    }
    download(filename = "meme") {
        setTimeout(() => {
            this.canvasDownloadLink.download = filename + ".png"
            this.canvasDownloadLink.href = this.canvas.toDataURL()
            this.canvasDownloadLink.click();
            this.select("#generated_canvas").remove();
        }, 600);
    }
}
