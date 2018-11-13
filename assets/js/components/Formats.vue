<template>
    <v-card>
        <v-card-title primary-title>
            <h3 :class="`headline ${color}--text`">
                Formats
            </h3>
        </v-card-title>

        <v-card-text primary-title class="justify-center pt-0">
            <v-radio-group v-model="format">
                <v-radio
                        :color="color"
                        v-for="(format,i) in formats"
                        :key="format"
                        :label="`${format}`"
                        :value="format"
                        @change="setFormat(format)"
                ></v-radio>
            </v-radio-group>

        </v-card-text>

    </v-card>
</template>

<script>

    export default{
        name:"formats",
        props:['color'],
        data(){
            return{
                formats : [
	                "default","Atom","Cookie","Iso8601",
	                "ISO","Iso8601Zulu","DateTimeLocal",
                    "Rfc822","Rfc850","Rfc1036","Rfc1123",
                    "Rfc2822","Rfc3339","Rfc7231","Rss","W3c"
                ],
                format: "default",
            }
        },
        methods : {
            setFormat(format) {
                this.$emit('set_format', {format})
            }
        },
        mounted () {
            this.setFormat(this.format)
        }
    }
</script>
