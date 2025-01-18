<template>
    <div class="flex flex-col h-full overflow-y-auto pb-12 bg-gradient-to-br bg-gray-900">
        <div>
            <div class="max-w-[100rem] mx-auto px-6 py-12">
                <div class="flex items-center justify-between">
                    <h2 class="uppercase text-white font-bold text-3xl">Shortify</h2>
                </div>
            </div>
        </div>
        <div>
            <div class="max-w-[100rem] mx-auto px-6">
                <UrlShortener/>
                <ShortUrlTable/>
            </div>
        </div>
    </div>
</template>

<script>
    import ShortUrlTable from '../components/ShortUrlTable.vue';
    import UrlShortener from '../components/UrlShortener.vue';

    export default {
        name: 'Home',
        components: {
            UrlShortener,
            ShortUrlTable
        },
    }
</script>
