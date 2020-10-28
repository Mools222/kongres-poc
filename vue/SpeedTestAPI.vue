<template>
    <div>
        <h1>Speed test</h1>
        <button @click="testSpeed">Test speed</button>
    </div>
</template>

<script>
export default {
    name: "SpeedTestAPI",
    data() {
        return {
            loadingTimesInSeconds: [],
        }
    },
    mounted() {
        console.log(customVars.baseUrl + '/wp-json/wp/v2/arrangement')
    },
    methods: {
        fetchArrangementsFromWordPressAPI: async function () {
            let startLoadingTime = Date.now();

            let response = await fetch(customVars.baseUrl + '/wp-json/wp/v2/event');
            let data = await response.json();

            // console.log(data);

            let endLoadingTime = Date.now();

            let loadingTimeInSeconds = (endLoadingTime - startLoadingTime) / 1000;

            this.loadingTimesInSeconds.push(loadingTimeInSeconds);
        },
        testSpeed: async function () {
            this.loadingTimesInSeconds = [];

            let iterations = 100;
            console.log("iterations", iterations);

            for (let i = 0; i < iterations; i++) {
                await this.fetchArrangementsFromWordPressAPI();
            }

            let totalLoadingTimeInSeconds = 0;
            for (let seconds of this.loadingTimesInSeconds) {
                totalLoadingTimeInSeconds += seconds;
            }
            let averageLoadingTimeInSeconds = totalLoadingTimeInSeconds / iterations;
            console.log("averageLoadingTimeInSeconds", averageLoadingTimeInSeconds);
        }
    }
}
</script>

<style scoped>

</style>