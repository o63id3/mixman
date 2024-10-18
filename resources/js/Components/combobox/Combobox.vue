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
import {
  Drawer,
  DrawerContent,
  DrawerTrigger,
  DrawerTitle,
  DrawerDescription,
} from '@/Components/ui/drawer'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import { CheckIcon } from 'lucide-vue-next'

import { cn } from '@/lib/utils'
import { CaretSortIcon } from '@radix-icons/vue'

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

const [StatusListTemplate, StatusList] = createReusableTemplate()
const [TriggerTemplate, Trigger] = createReusableTemplate()
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
    <StatusListTemplate>
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
                <div
                  class="rtl:truncate-rtl flex-1 ltr:truncate rtl:text-right"
                >
                  {{ option.label }}
                </div>
              </div>
            </CommandItem>
          </CommandGroup>
        </CommandList>
      </Command>
    </StatusListTemplate>

    <TriggerTemplate>
      <Button
        variant="outline"
        class="relative w-full justify-start hover:bg-background"
        :class="[!selected ? 'text-muted-foreground' : '']"
      >
        {{
          selected
            ? options.find((option) => option.value === selected)?.label
            : chooseTitle
        }}

        <CaretSortIcon
          :class="
            cn(
              'absolute left-5',
              'h-4 w-4 shrink-0 opacity-50',
              'ltr:me-2 ltr:ms-auto rtl:me-auto rtl:ms-2',
            )
          "
        />
      </Button>
    </TriggerTemplate>

    <Popover v-if="isDesktop" v-model:open="isOpen">
      <PopoverTrigger as-child>
        <Trigger />
      </PopoverTrigger>
      <PopoverContent class="w-full p-0" align="start">
        <StatusList />
      </PopoverContent>
    </Popover>

    <Drawer v-else v-model:open="isOpen">
      <DrawerTrigger as-child>
        <Trigger />
      </DrawerTrigger>
      <DrawerContent class="h-2/3">
        <DrawerTitle />
        <DrawerDescription />

        <div class="mt-4 border-t">
          <StatusList />
        </div>
      </DrawerContent>
    </Drawer>
  </div>
</template>
