<template>
    <div class="flex flex-col items-center justify-center relative">
        <h2 class="bg-gradient-to-r from-pink-500 via-blue-500 to-blue-900 uppercase text-transparent bg-clip-text font-bold text-6xl inline-block font-smooch tracking-wider">Shorten obnoxious URLs with ease</h2>

        <div class="flex flex-col w-full md:flex-row md:w-auto gap-6 mt-8">
            <div class="flex flex-col bg-gradient-to-r from-blue-800 via-blue-900 to-blue-900 p-[2px] rounded-lg md:w-[400px]">
                <h2 class="px-4 py-2 text-white text-sm font-bold">Original URL</h2>
                <div class="flex items-center bg-gray-900 rounded-[calc(0.5rem-2px)]">
                    <input v-model="url" type="text" class="bg-transparent outline-none flex-grow px-4 py-3 text-sm text-white" placeholder="Paste or type URL here">
                </div>
            </div>
            <div class="flex flex-col bg-gradient-to-r from-blue-800 via-blue-900 to-blue-900 p-[2px] rounded-lg md:w-[400px]">
                <h2 class="px-4 py-2 text-white text-sm font-bold">Alias (optional)</h2>
                <div class="flex items-center bg-gray-900 rounded-[calc(0.5rem-2px)] relative">
                    <h2 class="absolute left-4 pointer-events-none text-gray-300 text-sm" ref="shortUrlBase">{{ shortUrlBase }}</h2>
                    <input v-model="alias" type="text" class="px-4 py-3  bg-transparent outline-none flex-grow text-sm text-white" ref="shortUrlAliasInput" placeholder="Alias">
                </div>
            </div>
        </div>
        <button @click="shorten" class="bg-blue-900/40 py-2 px-4 text-gray-200 hover:text-white font-bold rounded-lg w-full md:w-[400px] mt-6 border border-b-2 border-blue-900 hover:bg-blue-900 hover:border-blue-900/40 transition ease duration-200">Shorten</button>

        <div class="flex items-center gap-4 bg-red-900 text-white px-4 py-2 absolute top-full mt-8 rounded-lg border border-b-2 border-red-800" v-if="error">
            <h2>Uh oh!</h2>
            <h2 class="text-gray-300">{{ error }}</h2>
        </div>
    </div>
</template>

<script>
    import http from '../http';
    import events from '../events';

    export default {
        name: 'UrlShortener',
        data() {
            return {
                shortUrlBase: window.shortUrlBase.replace('https://', '').replace('http://', ''),

                url: '',
                alias: '',

                error: null,
                errorTimeout: null
            }
        },
        mounted() {
            this.$nextTick(() => {
                this.applyShortUrlBasePadding();
            });
        },
        methods: {
            async shorten() {
                if(this.url.trim() === '') {
                    this.showErrorMessage('URL is required');
                    return;
                }

                if(!this.url.includes('http://') && !this.url.includes('https://')) {
                    this.url = 'http://' + this.url;
                }

                if(!this.validateUrl()) {
                    this.showErrorMessage('URL is invalid');
                    return;
                }

                try {
                    const response = await http.get('/encode', {
                        params: {
                            url: this.url,
                            alias: this.alias
                        }
                    });

                    if(response.status === 200) {
                        events.trigger('shortUrlCreated');
                        this.url = '';
                        this.alias = '';
                    } else {
                        this.showErrorMessage(response.data.error);
                    }
                } catch (e) {
                    this.showErrorMessage(e.response.data.error);
                }
            },
            showErrorMessage(error) {
                this.error = error;

                clearTimeout(this.errorTimeout);
                this.errorTimeout = setTimeout(() => {
                    this.error = null;
                }, 5000);
            },
            validateUrl() {
                console.log(this.url);

                try {
                    new URL(this.url);
                    return true;
                } catch (e) {
                    return false;
                }
            },
            applyShortUrlBasePadding() {
                const shortUrlBaseWidth = this.$refs.shortUrlBase.offsetWidth;
                this.$refs.shortUrlAliasInput.style.paddingLeft = shortUrlBaseWidth + 25 + 'px';
            }
        }
    }
</script>
