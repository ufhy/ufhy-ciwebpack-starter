<template>
  <v-container fill-height fluid class="pa-0">
    <v-layout wrap row>
      <v-flex md3>
        <v-card flat class="fill-height br-solid-1 blue-grey--border border--lighten-4">
          <v-card-title>
            <span class="d-inline mr-2">{{ $t('files::folders') }}</span>
            <v-progress-circular
              v-if="loadingFolder"
              indeterminate
              color="grey lighten-1"
              :size="20"
            ></v-progress-circular>
          </v-card-title>
          <v-card-text class="py-0">
            <v-treeview hoverable activatable transition return-object
              v-model="foldersTree"
              :open.sync="folderOpen"
              :items="foldersItems"
              :active.sync="folderActive"
              :load-children="fetchFolders"
              item-key="id">
              <template slot="prepend" slot-scope="props">
                <v-icon class="primary--text">
                  {{ props.open ? 'la-folder-open' : 'la-folder' }}
                </v-icon>
              </template>
            </v-treeview>
          </v-card-text>
        </v-card>
      </v-flex>

      <v-flex md9>
        <v-card flat class="fill-height">
          <v-card-title>
            <span class="d-inline mr-2">{{selectedBreadcrumb}}</span>
            <v-progress-circular
              v-if="loadingFile"
              indeterminate
              color="primary"
              :size="20"
            ></v-progress-circular>
            <v-spacer/>
            <v-menu offset-x left>
              <v-btn slot="activator" icon class="ma-0">
                <v-icon>la-navicon</v-icon>
              </v-btn>
              <v-list dense>
                <v-list-tile>
                  <v-list-tile-avatar size="">
                    <v-icon class="primary--text">la-folder-o</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-content>
                    <v-list-tile-title>{{ $t('files::new_folder') }}</v-list-tile-title>
                  </v-list-tile-content>
                </v-list-tile>
                <v-list-tile>
                  <v-list-tile-avatar size="">
                    <v-icon class="primary--text">la-cloud-upload</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-content>
                    <v-list-tile-title>{{ $t('files::upload_files') }}</v-list-tile-title>
                  </v-list-tile-content>
                </v-list-tile>
              </v-list>
            </v-menu>
            <v-btn icon class="ma-0">
              <v-icon>la-refresh</v-icon>
            </v-btn>
            <v-divider vertical class="mx-2"></v-divider>
            <v-btn icon 
              class="ma-0" 
              :class="{'primary--text': viewAs === 'card'}"
              @click="viewAs = 'card'">
              <v-icon>la-th-large</v-icon>
            </v-btn>
            <v-btn icon 
              class="ma-0" 
              :class="{'primary--text': viewAs === 'table'}"
              @click="viewAs = 'table'">
              <v-icon>la-th-list</v-icon>
            </v-btn>
          </v-card-title>

          <v-card-text>
            <template v-if="viewAs === 'table'">
              <v-data-table hide-actions
                class="uf-datatable"
                :items="selectedItems"
                :headers="tableView.headers">
                <template slot="items" slot-scope="props">
                  <td class="icon-label">
                    <div>
                      <template v-if="props.item.type === 'folder'">
                        <v-icon class="d-block primary--text" style="width: 30px">la-folder</v-icon>
                      </template>
                      <template v-if="props.item.type === 'file'">
                        <v-icon v-if="props.item.fileType === 'd'" class="d-block primary--text" style="width: 30px">la-file-text</v-icon>
                        <v-icon v-if="props.item.fileType === 'i'" class="d-block primary--text" style="width: 30px">la-file-image-o</v-icon>
                      </template>
                      <span>{{props.item.name}}</span>
                    </div>
                  </td>
                  <td class="text-truncate">
                    {{props.item.size}}
                  </td>
                  <td class="text-truncate">
                    {{props.item.updatedAt}}
                  </td>
                </template>
              </v-data-table>
            </template>

            <template v-if="viewAs === 'card'">
              <v-container fluid class="pa-0" grid-list-xl>
                <template v-if="selectedItemsFolder.length > 0">
                  <v-subheader>{{ $t('files::folders') }}</v-subheader>
                  <v-data-iterator hide-actions row wrap
                    :items="selectedItemsFolder"
                    content-tag="v-layout"
                    class="mb-4">
                    <template slot="item" slot-scope="props">
                      <v-flex xs12 md3>
                        <v-hover>
                          <v-card
                            slot-scope="{ hover }"
                            :class="`elevation-${hover ? 5 : 1}`">
                            <v-card-title>
                              <v-icon class="mr-3 primary--text">la-folder</v-icon>
                              {{ props.item.name }}
                            </v-card-title>
                          </v-card>
                        </v-hover>
                      </v-flex>
                    </template>
                  </v-data-iterator>
                </template>

                <template v-if="selectedItemsFile.length > 0">
                  <v-subheader>{{ $t('files::files') }}</v-subheader>
                  <v-data-iterator hide-actions row wrap
                    :items="selectedItemsFile"
                    content-tag="v-layout">
                    <template slot="item" slot-scope="props">
                      <v-flex xs12 md2>
                        <v-hover>
                          <v-card
                            slot-scope="{ hover }"
                            :class="`elevation-${hover ? 12 : 2}`">
                            <div v-if="props.item.fileType === 'd'" class="card-thumb">
                              <v-icon class="card-thumb-icon">la-file-text</v-icon>
                            </div>
                            <v-img v-if="props.item.fileType === 'i'"
                              :src="getImageUrl(props.item.id)"
                              aspect-ratio="2.75"
                              height="100px"
                            ></v-img>
                            <v-card-text class="text-truncate">
                              {{ props.item.name }}
                            </v-card-text>
                          </v-card>
                        </v-hover>
                      </v-flex>
                    </template>
                  </v-data-iterator>
                </template>
              </v-container>
            </template>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>

export default {
  name: 'files-page',
  components: {
    
  },
  data() {
    return {
      loadingFolder: false,
      loadingFile: false,
      foldersItems: [],
      foldersTree: [],
      folderOpen: [],
      folderActive: [],
      selected: {},
      indexFolder: {},
      tableView: {
        headers: [
          {text: this.$t('lb::name'), value: 'name', sortable: false},
          {text: this.$t('files::file_size'), value: 'size', sortable: false},
          {text: this.$t('files::updated_at'), value: 'updatedAt', sortable: false, width: "100px"},
        ]
      },
      viewAs: 'card'
    }
  },
  computed: {
    selectedFolderName() {
      return this.selected ? this.selected.name : ''
    },
    selectedBreadcrumb() {
      return this.getPath(this.selectedFolderName).join(' / ');
    },
    selectedItems() {
      const that = this;
      let items = [];
      if (typeof that.selected.folders !== "undefined") {
        const folders = that.selected.folders;
        if (that.$lodash.isArray(folders) && folders.length > 0) {
          folders.forEach(folder => {
            items.push({
              id: folder.id,
              name: folder.name,
              type: 'folder',
              size: '--',
              createdAt: that.$root.dateShort(folder.createdAt),
              updatedAt: that.$root.dateShort(folder.updatedAt),
            });
          });
        }
      }

      if (typeof that.selected.files !== "undefined") {
        const files = that.selected.files;
        if (that.$lodash.isArray(files) && files.length > 0) {
          files.forEach(file => {
            items.push({
              id: file.id,
              name: file.name,
              type: 'file',
              fileType: file.type,
              size: file.filesize + ' KB',
              createdAt: that.$root.dateShort(file.createdAt),
              updatedAt: that.$root.dateShort(file.updatedAt),
            });
          });
        }
      }

      return items;
    },
    selectedItemsFolder() {
      const items = this.selectedItems;
      const filters = items.filter(function(item) {
        return item.type === 'folder'
      });
      return filters;
    },
    selectedItemsFile() {
      const items = this.selectedItems;
      const filters = items.filter(function(item) {
        return item.type === 'file'
      });
      return filters;
    }
  },
  watch: {
    folderActive: {
      handler(value, old) {
        if (value !== old) {
          this.fetchFiles();
        }
      },
    }
  },
  async created() {
    this.loadingFolder = true;
    await this.fetchFolders();
    this.loadingFolder = false;
  },
  methods: {
    fetchFolders() {
      return this.$axios.get('files/folders')
        .then(response => {
          const { data } = response;
          this.foldersItems = data.folders;
          this.buildIndex(false, this.foldersItems);
          if (data.folders.length > 0) {
            this.folderActive.push(data.folders[0]);
          }
        })
        .catch(error => {
          console.log(error);
          const { statusText, status } = error;
          this.$ufsnackbars.error('Code: ' + status + ' ' + statusText);
        });
    },
    fetchFiles() {
      if (this.folderActive.length <= 0) {
        return false;
      }

      this.loadingFile = true;
      this.$axios.get('files', { params: {folder: this.folderActive[0].id} })
        .then(response => {
          const { data } = response;
          if (data.success) {
            this.selected = data.data;
          }
        })
        .catch(error => {
          const { statusText, status } = error;
          this.$ufsnackbars.error('Code: ' + status + ' ' + statusText);
        })
        .then(() => {
          this.loadingFile = false;
        })
    },
    buildIndex(root, children) {
      const that = this;
      for(var i in children) {
        that.indexFolder[children[i].name] = root;
        that.buildIndex(children[i].name, children[i].children ? children[i].children : []);
      }
    },
    getPath(leaf) {
      return this.indexFolder[leaf] 
        ? this.getPath(this.indexFolder[leaf]).concat([leaf]) 
        : [leaf];
    },
    getImageUrl(image) {
      return ufhy.SITE_URL + 'files/thumb/' + image
    }
  }
}
</script>

<style lang="scss" scoped>
.card-thumb {
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f5f5f5;
}
.card-thumb-icon {
  font-size: 80px;
}
</style>
