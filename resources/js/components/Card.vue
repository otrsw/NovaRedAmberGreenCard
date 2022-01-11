<template>
    <card class="border-2 border-pink-200 card card-panel flex flex-col items-center justify-center rounded-md">

    <span v-if="loading" class="mr-3 text-60">
        <svg class="w-12" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" ><circle cx="15" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="60" cy="15" r="9" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="105" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle></svg>
    </span>
    <div v-if="!loading && !error" class="flex flex-col h-full justify-between w-full ">
        <div class="flex flex-col w-full px-2 py-2">
            <div class="flex flex-row justify-between">
            <div :class="'animate-pulse w-10 h-10 inline-flex mb-2 items-center justify-center rounded-full bg-'+monitor_color+'-500'"></div>
            <div>
                <a :href="more_info_url" target="_blank"><svg class="h-10 w-10 float-right text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg></a>
            </div>
            </div>
            <div class="flex flex-col">
                <h5 class="text-base subpixel-antialiased text-pink-500 font-medium">{{monitor_name}}</h5>
                <p class="h-6 leading-relaxed overflow-y-auto overflow-x-hidden break-words text-sm">{{monitor_description}}</p>
            </div>
        </div>
        <div class="flex flex-row w-full justify-end">
            <span class="text-xs inline-block align-middle w-28 bg-pink-200 rounded-tl text-center text-gray-600">{{monitor_last_update}}</span>
        </div>
    </div>
    <div v-if="error" class="">
            <h5 class="font-medium text-base text-center text-red-500">{{error}}</h5>
    </div>
    </card>
</template>

<script>
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

export default {
    data(){
        return {
        loading:true,
        error:null,
        monitor:null,
        more_info_url : "#",
        monitor_name : "",
        monitor_description : "",
        monitor_last_update:"",
        monitor_color:"green",
        api_key:this.card.globalApiKey != null ? this.card.globalApiKey : this.card.apiKey,
        }
    },
    props: [
        'card',

        // The following props are only available on resource detail cards...
        // 'resource',
        // 'resourceId',
        // 'resourceName',
    ],
    methods: {
        loadMonitor(){
            const TOKEN = this.api_key;
            const BASEURL = 'https://app.red-amber.green/api';
            const ENDPOINT = '/monitors/'+this.card.monitorUuid;

            axios.create({
                baseURL: BASEURL,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer '+TOKEN
                }
            })
            .get(ENDPOINT)
            .then(res => {
                if(res.status === 200){
                    dayjs.extend(relativeTime);
                    this.monitor = res.data;
                    this.more_info_url = res.data.more_info_url;
                    this.monitor_name = res.data.name;
                    this.monitor_description = res.data.description;
                    this.monitor_last_update= dayjs(res.data.updated_at).fromNow();
                    this.monitor_color = res.data.status == "amber" ? "yellow" : res.data.status;
                }

                this.loading = false;
            }).catch(error =>{
                console.log("Opps we have an issue.");
                console.log(error);
                if(error.response.status === 404){
                    this.error = "Monitor with uuid '"+this.card.monitorUuid+"' not found";
                }
                if(error.response.status === 401){
                    this.error = "Invalid api token";
                }
                this.loading = false;
            });
        }
    },
    mounted() {
        if(!this.api_key){
            this.error = "Token not found."
        }
        this.loadMonitor();
    },
}
</script>
