<template>
  <v-container>
    <v-alert dismissible 
      v-if="$can('changes', $route.meta.module)"
      :value="true" 
      color="light-blue lighten-4" 
      class="mb-3">
      <span class="light-blue--text text--darken-4">
        {{$t('settings::information')}}
      </span>
    </v-alert>
    <v-card flat class="ba-solid-1 grey--border border--lighten-2 ba-round-sm pa-0">
      <v-tabs centered
        slider-color="primary"
        active-class="v-tabs__item--active primary--text">
        <v-tab v-for="(section, index) in sections"
          :key="index">
          {{section}}
        </v-tab>

        <v-tab-item v-for="(section, index) in sections"
          :key="index">
          <template v-if="dataSections[index]">
            <v-list two-line>
              <template v-for="(dataSection, indexData) in dataSections[index]">
                <setting-row 
                  :editable="$can('changes', $route.meta.module)"
                  :key="section + '_' + indexData"
                  :title="dataSection.title"
                  :description="dataSection.description"
                  :value="dataSection.value ? dataSection.value : dataSection.default"
                  :input-type="dataSection.type"
                  :input-options="dataSection.options"
                  @save="onSave(dataSection.slug, $event)"
                ></setting-row>
                <v-divider v-if="dataSections[index].length > indexData + 1" :key="section + '_divider_' + indexData" />
              </template>
            </v-list>
          </template>
        </v-tab-item>
      </v-tabs>
    </v-card>
  </v-container>
</template>

<script>
import SettingRow from '../../components/SettingRow.vue';
export default {
  name: 'settings-page',
  components: {
    SettingRow
  },
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
          this.$ufsnackbars.error('Code: ' + status + ' ' + statusText);
        });
    },
    onSave(slug, payload) {
      if (this.loading) {
        return false;
      }

      if (!slug) {
        return false;
      }

      this.loading = true;
      const item = new FormData();
      item.set('slug', slug);
      item.set('value', payload);

      this.$axios.post('settings/savechanges', item)
        .then((response) => {
          const { data } = response;
          this.$ufsnackbars.show(data.message, {type: data.success ? 'success' : 'error'});

          if (data.success) {
            this.fetchItem();
          }
        })
        .catch((error) => {
          const {statusText, data} = error;
          this.$ufsnackbars.error(statusText);
        })
        .then(() => {
          this.loading = false;
        }) 
    }
  }
}
</script>