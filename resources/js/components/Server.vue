<template>
    <div class="text-center p-5">
        <h1>{{ this.displayString }}</h1>
    </div>
</template>

<script>
export default {
    data() {
        return {
            lights: [],
            lastState: [
                [
                    {
                        id: 1,
                        on: 0,
                        bri: 0,
                        hue: 0,
                        sat: 0,
                        effect: "none",
                        x: 0,
                        y: 0
                    },
                    {
                        id: 2,
                        on: 0,
                        bri: 0,
                        hue: 0,
                        sat: 0,
                        effect: "none",
                        x: 0,
                        y: 0
                    },
                    {
                        id: 3,
                        on: 0,
                        bri: 0,
                        hue: 0,
                        sat: 0,
                        effect: "none",
                        x: 0,
                        y: 0
                    }
                ]
            ],

            displayString: "Serving..."
        }
    },
    created() {
        this.main();
    },
    methods: {

        main()
        {
            this.displayServing();
            setInterval(this.displayServing, 1000);

            this.getAndSetLights();
            setInterval(this.getAndSetLights, 3000);
        },

        getHTML(b, requestObject)
        {
            if(window.XMLHttpRequest)
            {
                var a=new XMLHttpRequest();
                a.open(b, 'http://192.168.0.101/api/v6LjMGOqrWBY440pvcpkFyd3K7uY6SQMeRWZJ6SA/lights/1/state', true);
                a.onreadystatechange = function()
                {
                    if(a.readyState == 4)
                    {
                        if(a.status == 200)
                        {
                            let data = JSON.parse(a.responseText);
                            //console.log(data);
                        }
                        else
                        {
                            console.log("Error " + a.status)
                        }
                    }
                };
                let postString = '{"on": ' + !!requestObject.on + ', "bri": ' + requestObject.bri + ', "hue": ' + requestObject.hue + ', "sat": ' + requestObject.sat + ', "xy": [' + requestObject.xy + '], "effect": "' + requestObject.effect + '"}';
                //console.log(postString);
                a.send(postString);
            }
            return false
        },

        /*
        PURPOSE OF "getAndSetLights":
        1. getting lights from philips HUE hub
        2. updatitng severs database
        */
        getAndSetLights() {

            //console.log("(LAN) GETTING LIGHTS FROM DATABASE");
            fetch('http://192.168.0.100/smarthome0/public/api/lights/')
            .then(res => res.json())
            .then(res => {
                
                let lightsFromDatabase = Object.values(res);

                if(this.stateHasChanged(lightsFromDatabase))
                {
                    console.log("(LAN) STATE CHANGED! UPDATING PHILIPS HUE BRIDGE");
                    for(var i = 0; i < 3; i++)
                    {
                        let requestObject = {
                            on: lightsFromDatabase[0][i].on,
                            bri: lightsFromDatabase[0][i].bri,
                            hue: lightsFromDatabase[0][i].hue,
                            sat: lightsFromDatabase[0][i].sat,
                            xy: [lightsFromDatabase[0][i].x, lightsFromDatabase[0][i].y],
                            effect: lightsFromDatabase[0][i].effect
                        }

                        this.getHTML('PUT', requestObject);
                    }
                }
                else
                {
                    console.log("STATE NOT CHANGED, STANDING BY...");
                }

                this.lastState = lightsFromDatabase;

            })
            .catch(err => console.log(err));
        },

        stateHasChanged(lightsFromDatabase)
        {

            for(var i = 0; i < lightsFromDatabase.length; i++)
            {
                let onHasChanged = !(this.lastState[0][i].on == lightsFromDatabase[0][i].on);
                let briHasChanged = !(this.lastState[0][i].bri == lightsFromDatabase[0][i].bri);
                let hueHasChanged = !(this.lastState[0][i].hue == lightsFromDatabase[0][i].hue);
                let satHasChanged = !(this.lastState[0][i].sat == lightsFromDatabase[0][i].sat);
                let xHasChanged = !(this.lastState[0][i].x == lightsFromDatabase[0][i].x);
                let yHasChanged = !(this.lastState[0][i].y == lightsFromDatabase[0][i].y);
                let effectHasChanged = !(this.lastState[0][i].effect == lightsFromDatabase[0][i].effect);
                let methodHasChanged = !(this.lastState[0][i].method == lightsFromDatabase[0][i].method);

                if(onHasChanged || briHasChanged || hueHasChanged || satHasChanged || xHasChanged || yHasChanged || effectHasChanged)
                {
                    return true;
                }
            }

            return false;
        },

        hueToRGB()
        {
            // For the hue bulb the corners of the triangle are:
            // -Red: 0.675, 0.322
            // -Green: 0.4091, 0.518
            // -Blue: 0.167, 0.04
            let normalizedToOne = [];
            let cred, cgreen, cblue;
            cred = 255;
            cgreen = 0;
            cblue = 0;
            normalizedToOne[0] = (cred / 255);
            normalizedToOne[1] = (cgreen / 255);
            normalizedToOne[2] = (cblue / 255);
            let red, green, blue;

            // Make red more vivid
            if (normalizedToOne[0] > 0.04045) {
                red = Math.pow(
                        (normalizedToOne[0] + 0.055) / (1.0 + 0.055), 2.4);
            } else {
                red = (normalizedToOne[0] / 12.92);
            }

            // Make green more vivid
            if (normalizedToOne[1] > 0.04045) {
                green = Math.pow((normalizedToOne[1] + 0.055)
                        / (1.0 + 0.055), 2.4);
            } else {
                green = (normalizedToOne[1] / 12.92);
            }

            // Make blue more vivid
            if (normalizedToOne[2] > 0.04045) {
                blue = Math.pow((normalizedToOne[2] + 0.055)
                        / (1.0 + 0.055), 2.4);
            } else {
                blue = (normalizedToOne[2] / 12.92);
            }

            let X = (red * 0.649926 + green * 0.103455 + blue * 0.197109);
            let Y = (red * 0.234327 + green * 0.743075 + blue * 0.022598);
            let Z = (red * 0.0000000 + green * 0.053077 + blue * 1.035763);

            let x = X / (X + Y + Z);
            let y = Y / (X + Y + Z);

            xy = [];
            xy[0] = x;
            xy[1] = y;
            let xyAsList = [x, y];
            return xyAsList;
        },

        displayServing() {

            if(this.displayString == "Serving")
            {
                this.displayString = "Serving.";
            }
            else if(this.displayString == "Serving.")
            {
                this.displayString = "Serving..";
            }
            else if(this.displayString == "Serving..")
            {
                this.displayString = "Serving...";
            }
            else {
                this.displayString = "Serving";
            }
        }
    }
}
</script>

<style lang="stylus" scoped>

</style>