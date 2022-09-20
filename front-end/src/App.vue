<template>
  <router-view/>
</template>
<script lang="ts">
import { defineComponent, onMounted } from 'vue';
import { mainStore } from './store/mainStore';
export default defineComponent({
  setup () {
    const store = mainStore();
    onMounted(() => {
      if(process.env.NODE_ENV === "development") {
        store.setUrl("http://localhost:8000/api/");
        document.body.classList.add('debug-screens');
      }
      const color_scheme = window.matchMedia('(prefers-color-scheme: dark)');
      if(color_scheme.matches) {
        document.body.classList.add('dark-bg');
        document.body.classList.remove('light-bg');
      } else {
        document.body.classList.add('light-bg');
        document.body.classList.remove('dark-bg');
      }
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener("change", event => {
        const colorScheme = event.matches ? true : false;
        if(colorScheme) {
          document.body.classList.add('dark-bg');
          document.body.classList.remove('light-bg');
        } else {
          document.body.classList.add('light-bg');
          document.body.classList.remove('dark-bg');
        }
      });
    });
  }
})
</script>
<style>
.dark-bg {
  background: url('@/assets/dark.svg');
  background-repeat: no-repeat;
  background-position: bottom right;
  /* background-size: cover; */
}

.light-bg {
  background: url('@/assets/light.svg');
  background-repeat: no-repeat;
  background-position: bottom right;
  /* background-size: cover; */
}
</style>