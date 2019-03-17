<template>
  <v-dialog persistent lazy
    :value="isShow"
    content-class="v-dialog--top"
    transition="slide-y-transition"
    max-width="400px">
    <v-card class="card-dialog">
      <v-card-title>
        <span class="subheading">{{ title }}</span>
        <v-spacer />
        <v-btn icon small @click="isShow = false">
          <v-icon>la-times</v-icon>
        </v-btn>
      </v-card-title>
      <v-card-text>
        <v-text-field solo autofocus ref="inputBox"
          v-model="inputText"
          :placeholder="placeholder"
        ></v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-btn small depressed @click="isShow = false">
          Cancel
        </v-btn>
        <v-btn small depressed color="primary" @click="submitAction">
          {{ submitText }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'files-dialog-new-folder',
  props: {
    value: Boolean,
    title: String,
    placeholder: String,
    submitText: String
  },
  data() {
    return {
      inputText: '',
    }
  },
  computed: {
    isShow: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      }
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.$refs.inputBox.focus();
    })
  },
  methods: {
    submitAction() {
      this.$emit('save', this.inputText);
      this.isShow = false;
    }
  }
}
</script>
