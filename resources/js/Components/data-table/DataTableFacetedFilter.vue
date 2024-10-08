<script setup lang="ts" generic="TData, TValue">
import type { Column } from '@tanstack/vue-table'
import type { Component } from 'vue'
import { computed } from 'vue'
import { PlusCircledIcon, CheckIcon } from '@radix-icons/vue'

import { Badge } from '@/Components/ui/badge'
import { Button } from '@/Components/ui/button'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
  CommandSeparator,
} from '@/Components/ui/command'

import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import { Separator } from '@/Components/ui/separator'
import { cn } from '@/lib/utils'

interface DataTableFacetedFilter {
  column?: Column<TData, TValue>
  title?: string
  options: {
    label: string
    value: string
    icon?: Component
  }[]
}

const props = defineProps<DataTableFacetedFilter>()

const facets = computed(() => props.column?.getFacetedUniqueValues())
const selectedValues = computed(() => {
  const values = props.column?.getFilterValue() as string
  return values ? new Set(values.split(',')) : new Set()
})

const filterFunction = (val: any[], term: string) => {
  return val.filter((item) => {
    if (typeof item === 'object' && 'label' in item) {
      return item.label.toLowerCase().includes(term.toLowerCase())
    }
    return false
  })
}
</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button variant="outline" size="sm" class="h-8 border-dashed">
        <PlusCircledIcon class="h-4 w-4 ltr:mr-2 rtl:ml-2" />
        {{ title }}
        <template v-if="selectedValues.size > 0">
          <Separator orientation="vertical" class="mx-2 h-4" />
          <Badge
            variant="secondary"
            class="rounded-sm px-1 font-normal lg:hidden"
          >
            {{ selectedValues.size }}
          </Badge>
          <div class="hidden space-x-1 lg:flex">
            <Badge
              v-if="selectedValues.size > 2"
              variant="secondary"
              class="rounded-sm px-1 font-normal"
            >
              {{ selectedValues.size }} مختار
            </Badge>

            <template v-else>
              <div class="flex items-center gap-0.5">
                <Badge
                  v-for="option in options.filter((option) =>
                    selectedValues.has(option.value),
                  )"
                  :key="option.value"
                  variant="secondary"
                  class="rounded-sm px-1 font-normal"
                >
                  {{ option.label }}
                </Badge>
              </div>
            </template>
          </div>
        </template>
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-[200px] p-0" align="start">
      <Command :filter-function="filterFunction">
        <!-- <CommandInput class="rtl:text-right" :placeholder="title" /> -->
        <CommandList>
          <CommandEmpty>لا يوجد نتائج</CommandEmpty>
          <CommandGroup>
            <CommandItem
              v-for="option in options"
              :key="option.value"
              :value="option"
              @select="
                () => {
                  const isSelected = selectedValues.has(option.value)
                  if (isSelected) {
                    selectedValues.delete(option.value)
                  } else {
                    selectedValues.add(option.value)
                  }
                  const filterValues = Array.from(selectedValues)
                  column?.setFilterValue(filterValues.join(','))
                }
              "
            >
              <div
                :class="
                  cn(
                    'flex h-4 w-4 items-center justify-center rounded-sm border border-primary ltr:mr-2 rtl:ml-2',
                    selectedValues.has(option.value)
                      ? 'bg-primary text-primary-foreground'
                      : 'opacity-50 [&_svg]:invisible',
                  )
                "
              >
                <CheckIcon :class="cn('h-4 w-4')" />
              </div>
              <component
                :is="option.icon"
                v-if="option.icon"
                class="h-4 w-4 ltr:mr-2 rtl:ml-2"
              />
              <span dir="rtl" class="rtl:truncate-rtl ltr:truncate">
                {{ option.label }}
              </span>
              <span
                v-if="facets?.get(option.value)"
                class="font-mono flex h-4 w-4 items-center justify-center text-xs ltr:ml-auto rtl:mr-auto"
              >
                {{ facets.get(option.value) }}
              </span>
            </CommandItem>
          </CommandGroup>

          <template v-if="selectedValues.size > 0">
            <CommandSeparator />
            <CommandGroup>
              <CommandItem
                :value="{ label: 'Clear filters' }"
                class="justify-center text-center"
                @select="column?.setFilterValue(undefined)"
              >
                مسح
              </CommandItem>
            </CommandGroup>
          </template>
        </CommandList>
      </Command>
    </PopoverContent>
  </Popover>
</template>
