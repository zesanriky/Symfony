<template>
    <v-container class="fill-height">
        <v-content class="fill-height">
            <v-layout justify-center mb-3>
                <h1 :class="`display-2 ${color}--text `">Welcome to the <span>Code Challenge</span></h1>
            </v-layout>
            <v-layout row wrap mt-3>
                <p>
                    <strong>Create RESTful API with swagger</strong>
                </p>

                <ol>
                    <li>Development Symfony based application with RESTful API, Swagger documentation to demonstrate the API, one service to get the current date-time.</li>
                    <br>
                    <li>RESTful service that sends the current date time from the browser-client to the server and logs it to the PSR-3 logger.</li>
                </ol>

            </v-layout>
            <v-layout justify-center>
                <v-radio-group row v-model="color" class="justify-center">
                    <v-radio
                            v-for="color in colors"
                            :key="color"
                            :value="color"
                            :color="color"

                    >
                        <span slot="label" :class="`${color}--text`">{{ color }}</span>
                    </v-radio>
                </v-radio-group>
            </v-layout>
            <v-layout fill-height justify-space-between align-center>

                <v-flex xs6>
                    <v-tabs
                            centered
                            :color="color"
                            dark
                    >
                        <v-tabs-slider color="white"></v-tabs-slider>

                        <v-tab href="#log">
                            Logging
                        </v-tab>

                        <v-tab href="#retrieve">
                            Retrieving
                        </v-tab>

                        <v-tab-item
                               value="log"
                               class=""
                        >
                            <v-card>

                                <v-card-text primary-title class="justify-center">

                                    <h2 class="display-3 mb-4 text-xs-center teal--text">{{ datetime }}</h2>

                                </v-card-text>
                                <span class="red--text" v-if="error.show">code: {{ error.code }} <br> message: <span v-html="error.message"></span> </span>
                                <v-divider></v-divider>
                                <v-card-actions>
                                    <v-btn tag="a" href="./_profiler" target="_blank" :color="color" flat>Check the profiler</v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn  @click="logDatetime" :loading="logging" :color="color" class="white--text">Log</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item
                                value="retrieve"
                        >
                            <v-card>

                                <v-card-text primary-title class="justify-center">

                                    <h2 v-if="current" :class="`headline mb-4 text-xs-center ${color}--text`">{{ current }}</h2>
                                    <h2 v-else class="headline mb-4 text-xs-center grey--text"> You Have to make a request</h2>

                                </v-card-text>

                                <span class="red--text" v-if="error.show">code: {{ error.code }} <br> message: <span>{{ current }}</span> </span>

                                <v-divider></v-divider>

                                <v-card-actions>
                                    <v-btn tag="a" href="./_profiler" target="_blank" :color="color" flat>Check the profiler</v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn  @click="getCurrent" :loading="retrieving" :color="color" class="white--text">Retrieve</v-btn>
                                </v-card-actions>

                            </v-card>
                        </v-tab-item>
                    </v-tabs>


                </v-flex>

                <v-flex xs3>

                    <formats @set_format="setFormat" :color="color"></formats>

                </v-flex>

            </v-layout>

        </v-content>

    </v-container>
</template>

<script>
    const format = "YYYY-MM-D H:m:s";

    import Formats from './Formats.vue'
    export default {
        components: {
            Formats
        },
        data () {
            return {
                datetime: moment().format(format),
                current:null,
                logging:false,
                retrieving:false,
                colors: [
                    "teal",
                    "orange",
                    "blue",
                    "yellow",
                    "cyan",
                    "green",
                    "purple",
                    "grey"
                ],
                v:0,
                color: 'teal',
                format: null,
                error: {
                    show: false,
                    code:null,
                    message:""
                }
            }
        },
        methods : {
            setFormat ({format}) {
                this.format = format
            },
            logDatetime () {

                this.logging = true;

                axios
                .post(`api/v1/current/${this.format}/log`,{datetime: this.datetime})
                .then((data) => {
                    this.logging = false
                }).catch(err => {

                    this.logging = false
                    this.error.show = true
                    this.error.code = err.response.status
                    this.error.message = err.response.data.message

                })
            },
            getCurrent () {
                this.retrieving = true;

                axios
                .get(`api/v1/current/${this.format}`)
                .then((data) => {
                    this.retrieving = false
                    this.current = data.data.current
                }).catch(err => {

                    this.retrieving = false
                    this.error.show = true
                    this.error.code = err.response.status
                    this.error.message = err.response.data.message

                })
            }
        },
        mounted () {
            setInterval(() => {
                this.datetime = moment().format(format)
            },1000);
        }
    }
</script>