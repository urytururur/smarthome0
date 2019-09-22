<template>
    <div class="text-center p-5">
        <h1>Serving...</h1>
    </div>
</template>

<script>
export default {
    data() {
        return {
            lights: []
        }
    },
    created() {
        this.getAndSetLights();
        setInterval(this.getAndSetLights, 3000);
    },
    methods: {
        /*
        PURPOSE OF "getAndSetLights":
        1. getting lights from philips HUE hub
        2. updatitng severs database
        */
        getAndSetLights() {

            //getting lights from philips HUE hub
            console.log("GETTING LIGHTS FROM HUB (LAN)")
            fetch('http://192.168.0.101/api/v6LjMGOqrWBY440pvcpkFyd3K7uY6SQMeRWZJ6SA/lights')
            .then(res => res.json())
            .then(res => {  

                let lightsFromRouter = Object.values(res);

                //preparing post statement
                let index = 1;
                lightsFromRouter.forEach(function(element) {

                    var data = {
                        light_id: index,
                        on: element.state.on,
                        bri: element.state.bri,
                        hue: element.state.hue,
                        sat: element.state.sat,
                    };

                    //updating database
                    console.log("UPDATING DATABASE (LAN)")
                    fetch('http://192.168.100/smarthome0/public/api/light', {
                        method: 'PUT', // or 'PUT'
                        body: JSON.stringify(data), // data can be `string` or {object}!
                        headers:{
                            'Content-Type': 'application/json'
                        }
                    }).then(res => res.json())
                    //.then(response => console.log('Success:', JSON.stringify(response))) // SHOW UPDATE-VALUES IN CONSOLE
                    .catch(error => console.error('Error:', error));
                    index++;

                });
            })
            .catch(err => console.log(err));
        },
    }
}
</script>

<style lang="stylus" scoped>

</style>