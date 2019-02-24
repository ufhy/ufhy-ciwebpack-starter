<template>
  <v-container fluid>
    <v-card>
      <v-card-text>
      <v-tabs centered
        slider-color="primary"
        active-class="v-tabs__item--active primary--text">
        <v-tab v-for="(section, index) in sections"
          :key="index">
          {{section}}
        </v-tab>

        <v-tab-item v-for="(section, index) in sections"
          :key="index">
          {{section}}
        </v-tab-item>
      </v-tabs>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: 'settings-page',
  data() {
    return {
      loading: false,
      sections: [],
      dataSections: [],
    }
  },
  async created() {
    this.loading = true;
    await this.fetchItem();
    this.loading = false;
  },
  methods: {
    fetchItem() {
      return this.$axios.get('settings')
        .then(response => {
          const { data } = response;
          if (data.success) {
            this.sections = data.data.sections;
            this.dataSections = data.data.dataSection;
          }
        })
        .catch(error => {
          const { statusText, status } = error;
          that.$ufsnackbars.error('Code: ' + status + ' ' + statusText);
        });
    }
  }
}
</script>