<template>
    <div class="px-3 py-2 flex flex-col w-full">
        <div class="flex flex-col bg-slate-200 dark:bg-slate-500 dark:border-slate-600 w-full px-3 py-2 shadow border cursor-pointer relative">
            <div class="flex flex-row items-center justify-between relative">
                <div class="absolute top-0 left-0 right-0 bottom-0" @click="showOptions = !showOptions"></div>
                <h3 class="font-bold text-gray-800 dark:text-gray-200 truncate">{{ selectedDepartament ? selectedDepartament.description : 'Alege departamentul'}}</h3>
                <div class="flex flex-col">
                    <i class="text-blue-500 dark:text-sky-500 fa-solid fa-sort-up"></i>
                    <i class="text-blue-500 dark:text-sky-500 fa-solid fa-sort-down"></i>
                </div>
            </div>
            <div class="absolute left-0 right-0 top-14" v-if="showOptions">
                <div class="flex flex-col bg-slate-200 dark:bg-slate-500 dark:border-slate-600 shadow border px-3 py-2 max-h-96 overflow-y-scroll space-y-2">
                    <input type="text" class="px-3 py-2 outline-sky-500 dark:bg-gray-200 dark:text-gray-700" v-model="query" placeholder="Cautare...">
                    <div class="flex flex-row px-2 py-1 border dark:border-gray-500 hover:bg-sky-500 group" @click="setSelectedDepartament(departament)" v-for="departament in filteredDepartaments" :key="departament.id">
                        <p class="text-sm group-hover:text-white dark:text-gray-200" 
                            :class="{'font-bold': selectedDepartament && selectedDepartament.id === departament.id, 'font-semibold': !selectedDepartament}">
                            <span class="text-green-500 mr-2" v-if="selectedDepartament && selectedDepartament.id === departament.id"><i class="fa-solid fa-check"></i></span>
                            {{ departament.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, computed, ref } from 'vue';
import { mainStore } from '@/store/mainStore';
import { storeToRefs } from 'pinia';
import { SelectedDepartamentInterface } from '@/interfaces/mainStore.interface';
import localforage from 'localforage';

export default defineComponent({
    components: {

    },
    setup() {
        const store = mainStore();
        const { departaments, selectedDepartament } = storeToRefs(store);
        const query = ref<string>('');
        const showOptions = ref<boolean>(false);

        const checkDefault = async () => {
            try {
                const defaultDepartament:string|null = await localforage.getItem(`default_departament`);
                if(defaultDepartament) {
                    const parsed:SelectedDepartamentInterface = JSON.parse(defaultDepartament);
                    selectedDepartament.value = parsed;
                }
            } catch (exception) {   
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const fetchDepartaments = async () => {
            try {
                const request = await fetch(`${store.$state.url}v1/departaments/get_departaments?division_id=1`);
                const response = await request.json();
                if(process.env.NODE_ENV) console.log(response);
                if(response.success) {
                    store.setDepartaments(response.data);
                    
                }
            } catch(exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const setSelectedDepartament = async (departament:SelectedDepartamentInterface) => {
            try {
                store.setSelectedDepartament(departament);
                showOptions.value = false;
                const jsonDepartament = JSON.stringify(departament);
                await localforage.setItem(`default_departament`, jsonDepartament);
                
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        onMounted(() => {
            fetchDepartaments();
            checkDefault();
        });

        const filteredDepartaments = computed(() => {
            return query.value === ''
                ? departaments.value
                : departaments.value.filter((departament) => {
                    return departament.description.toLowerCase().includes(query.value.toLowerCase())
                })
        });

        return {
            filteredDepartaments,
            query,
            setSelectedDepartament,
            selectedDepartament,
            showOptions
        }
    }
});
</script>