<template>
    <div class="flex flex-col">
        <div class="flex flex-col items-center justify-center h-[300px] bg-blue-900/5 border border-b-2 border-gray-800 mt-20" v-if="loading">
            <i class="bx bx-loader-alt bx-spin text-gray-300 text-4xl"></i>
            <h2 class="text-white uppercase mt-4">Loading Links...</h2>
        </div>

        <div class="flex flex-col mt-20" v-else>
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <h2 class="text-white text-2xl font-bold">Shortened Links</h2>
                <div class="flex items-center relative">
                    <i class="bx bx-search text-gray-300 text-lg absolute left-4"></i>
                    <input v-model="query" @keydown.enter="search" type="text" class="bg-gray-800/20 outline-none px-12 py-3 text-white text-sm border border-b-2 w-full mt-2 md:mt-0 md:w-[300px] border-blue-900/20" placeholder="Search for a link (press enter)">
                    <i class="bx bx-loader-alt bx-spin text-gray-300 text-lg absolute right-4 cursor-pointer" v-if="searching"></i>
                    <i @click="clearSearch" class="bx bx-x text-gray-300 text-lg absolute right-4 cursor-pointer" v-if="searchedQuery && !searching"></i>
                </div>
            </div>
            <div class="flex flex-col overflow-x-auto">
                <table class="w-full mt-4">
                    <thead class="bg-blue-900/40 border border-b-2 border-blue-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-white">Original URL</th>
                            <th class="px-4 py-3 text-left text-white">Short URL</th>
                            <th class="px-4 py-3 text-left text-white min-w-[200px]">Created</th>
                            <th class="px-4 py-3 text-right text-white">Uses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="pagination?.data.length === 0" class="bg-black/20 border border-gray-800 text-sm">
                            <td class="py-12" colspan="4">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bx bx-sad text-white text-5xl"></i>
                                    <h2 class="text-white text-center mt-4" v-if="!searchedQuery">There are no shortened links... what a sad day!</h2>
                                    <h2 class="text-white text-center mt-4" v-else>We couldn't find any matching shortened links... how sad!</h2>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="link in pagination?.data" :key="link.id" class="even:bg-blue-900/5 odd:bg-blue-900/10 border border-gray-800 text-sm">
                            <td class="py-1">
                                <div class="flex items-center px-4 py-2 gap-2">
                                    <div class="flex items-center justify-center w-[30px] h-[30px] mr-2">
                                        <img class="h-6" :src="link.domain + '/favicon.ico'">
                                    </div>
                                    <div @click="copyToClipboard(link.original_url)" class="cursor-pointer flex items-center px-3 py-1 gap-2 bg-gray-900 hover:bg-blue-900/20 transition ease duration-200 border border-b-2 border-blue-900/40 text-xs group">
                                        <h2 class="text-white">{{ link.original_url }}</h2>
                                        <i class="bx bx-clipboard text-gray-500 group-hover:text-white transition ease duration-200 text-lg"></i>
                                    </div>
                                    <a :href="link.original_url" target="_blank" class="bg-gray-900 hover:bg-blue-900/10 border border-b-2 border-blue-900/40 px-3 py-1 text-gray-500 hover:text-white "><i class="bx bx-link-external text-lg transition ease duration-200"></i></a>
                                </div>
                            </td>
                            <td class="py-1">
                                <div class="flex items-center gap-2 px-4">
                                    <div @click="copyToClipboard(link.short_url)" class="cursor-pointer flex items-center px-3 py-1 gap-2 bg-gray-900 hover:bg-blue-900/20 transition ease duration-200 border border-b-2 border-blue-900/40 text-xs group">
                                        <h2 class="text-white">{{ link.short_url }}</h2>
                                        <i class="bx bx-clipboard text-gray-500 group-hover:text-white transition ease duration-200 text-lg"></i>
                                    </div>
                                    <a :href="link.short_url" target="_blank" class="bg-gray-900 hover:bg-blue-900/10 border border-b-2 border-blue-900/40 px-3 py-1 text-gray-500 hover:text-white "><i class="bx bx-link-external text-lg transition ease duration-200"></i></a>
                                </div>
                            </td>
                            <td class="py-1 min-w-[200px]">
                                <div class="flex items-center gap-2 px-4">
                                    <i class="bx bx-time text-gray-300 text-lg"></i>
                                    <h2 class="text-white">{{ link.time_ago }}</h2>
                                </div>
                            </td>
                            <td class="py-1">
                                <h2 class="px-4 py-2 text-white text-right">{{ link.uses }}</h2>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="flex items-center justify-between mt-4">
                <button @click="prevPage" :class="{
                    'bg-blue-900/20 text-gray-200 hover:text-white border border-b-2 border-gray-800 hover:bg-blue-900/40 hover:border-blue-900/40': pagination?.meta.current_page > 1,
                    'bg-gray-800/20 text-gray-400 cursor-not-allowed border border-b-2 border-gray-800/50': pagination?.meta.current_page === 1
                }" class="py-1 px-4 transition ease duration-200 text-xl"><i class="bx bx-chevron-left relative"></i></button>

                <div class="flex flex-col items-center">
                    <h2 class="text-white text-xs">Page {{ pagination?.meta.current_page }} of {{ pagination?.meta.last_page }}</h2>
                    <h2 class="text-gray-500 text-xs mt-1">{{ pagination?.meta.total }} shortened link(s) <span v-if="searchedQuery">matching "{{ searchedQuery }}"</span></h2>
                </div>

                <button @click="nextPage" :class="{
                    'bg-blue-900/20 text-gray-200 hover:text-white border border-b-2 border-gray-800 hover:bg-blue-900/40 hover:border-blue-900/40': pagination?.meta.current_page < pagination?.meta.last_page,
                    'bg-gray-800/20 text-gray-400 cursor-not-allowed border border-b-2 border-gray-800/50': pagination?.meta.current_page === pagination?.meta.last_page
                }" class="py-1 px-4 transition ease duration-200 text-xl"><i class="bx bx-chevron-right relative"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    import events from '../events';
    import http from '../http';

    export default {
        name: 'ShortUrlTable',
        data() {
            return {
                query: '',
                searchedQuery: null,
                linkPage: 1,
                perPage: 10,

                pagination: null,

                loading: true,
                searching: false
            }
        },
        mounted() {
            this.load();

            events.on('shortUrlCreated', () => {
                this.query = '';
                this.linkPage = 1;
                this.load();
            });
        },
        methods: {
            async load() {
                this.loading = true;
                await this.fetchLinks();
                this.loading = false;
            },
            async search() {
                this.searching = true;
                await this.fetchLinks();
                this.searching = false;
            },
            async fetchLinks() {
                try {
                    const response = await http.get('links', {
                        params: {
                            page: this.linkPage,
                            limit: this.perPage,
                            query: this.query
                        }
                    });
                    this.pagination = response.data;

                    if(this.searchedQuery !== this.query) {
                        this.searchedQuery = this.query;
                    }
                } catch (e) {
                    console.error(e);
                }
            },
            nextPage() {
                if(this.pagination.meta.current_page < this.pagination.meta.last_page) {
                    this.linkPage++;
                    this.fetchLinks();
                }
            },
            prevPage() {
                if(this.pagination.meta.current_page > 1) {
                    this.linkPage--;
                    this.fetchLinks();
                }
            },
            copyToClipboard(text) {
                try {
                    navigator.clipboard.writeText(text);
                } catch (e) {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';  // Avoid scrolling to bottom
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();

                    try {
                        document.execCommand('copy');
                    } catch (err) {
                        alert('Failed to copy text to clipboard');
                    }

                    document.body.removeChild(textArea);
                }
            },
            clearSearch() {
                this.query = '';
                this.search();
            }
        }
    }
</script>
