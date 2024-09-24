<script lang="ts" setup>
import { createReusableTemplate, useMediaQuery } from '@vueuse/core'
import { computed, ref } from 'vue'
import { Button } from '@/Components/ui/button'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from '@/Components/ui/command'
import { Drawer, DrawerContent, DrawerTrigger } from '@/Components/ui/drawer'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import { cn } from '@/lib/utils'
import { CheckIcon } from 'lucide-vue-next'

interface Option {
  label: string
  value: any
}

const props = defineProps<{
  options: Array<Option>
  chooseTitle: string
  searchPlaceholder: string
  selected: any
}>()

const emit = defineEmits(['select'])

const [UseTemplate, StatusList] = createReusableTemplate()
const isDesktop = useMediaQuery('(min-width: 768px)')

const isOpen = ref(false)

function onStatusSelect(option: Option) {
  emit('select', option)
  isOpen.value = false
}

const searchQuery = ref('')

const filteredOptions = computed(() => {
  if (!searchQuery.value) return props.options
  return props.options.filter((option) =>
    option.label.toLowerCase().includes(searchQuery.value.toLowerCase()),
  )
})

function filter(value: string, search: string) {
  return value.toLowerCase().includes(search.toLowerCase())
}
</script>

<template>
  <div>
    <UseTemplate>
      <Command :filter="filter">
        <!-- <CommandInput
          v-model="searchQuery"
          class="rtl:text-right"
          :placeholder="searchPlaceholder"
        /> -->
        <CommandList>
          <CommandEmpty>لا يوجد نتائج.</CommandEmpty>
          <CommandGroup>
            <CommandItem
              v-for="option of filteredOptions"
              :key="option.value"
              :value="option.label"
              @select="onStatusSelect(option)"
            >
              <div class="flex w-full items-center justify-between">
                <CheckIcon
                  :class="
                    cn(
                      'ml-auto h-4 w-4',
                      selected === option.value ? 'opacity-100' : 'opacity-0',
                    )
                  "
                />
                <div class="flex-1 rtl:text-right">
                  {{ option.label }}
                </div>
              </div>
            </CommandItem>
          </CommandGroup>
        </CommandList>
      </Command>
    </UseTemplate>

    <Popover v-if="isDesktop" v-model:open="isOpen">
      <PopoverTrigger as-child>
        <Button
          variant="outline"
          class="w-full justify-start text-muted-foreground"
        >
          {{
            selected
              ? options.find((option) => option.value === selected)?.label
              : chooseTitle
          }}
        </Button>
      </PopoverTrigger>
      <PopoverContent class="w-full p-0" align="start">
        <StatusList />
      </PopoverContent>
    </Popover>

    <Drawer v-else v-model:open="isOpen">
      <DrawerTrigger as-child>
        <Button
          variant="outline"
          class="w-full justify-start text-muted-foreground"
        >
          {{
            selected
              ? options.find((option) => option.value === selected)?.label
              : chooseTitle
          }}
        </Button>
      </DrawerTrigger>
      <DrawerContent>
        <div class="mt-4 border-t">
          <StatusList />
        </div>
      </DrawerContent>
    </Drawer>
  </div>
</template>
