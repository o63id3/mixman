<script setup lang="ts">
import { useMediaQuery } from '@vueuse/core'
import { computed } from 'vue'
import { Calendar as CalendarIcon } from 'lucide-vue-next'
import type { DateRange } from 'radix-vue'
import { DateFormatter, getLocalTimeZone } from '@internationalized/date'

import { cn } from '@/lib/utils'
import { Button } from '@/Components/ui/button'
import { RangeCalendar } from '@/Components/ui/range-calendar'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import Separator from './ui/separator/Separator.vue'
import Badge from './ui/badge/Badge.vue'

const props = defineProps<{
  modelValue: DateRange | undefined
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: DateRange): void
}>()

const model = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value as DateRange),
})

const df = new DateFormatter('ar-US', {
  dateStyle: 'long',
})

const isDesktop = useMediaQuery('(min-width: 768px)')

const formattedDateRange = computed(() => {
  if (!isDesktop.value) return 'التاريخ'
  if (!model.value?.start) return 'التاريخ'

  const start = df.format(model.value.start.toDate(getLocalTimeZone()))
  if (!model.value.end) return start

  const end = df.format(model.value.end.toDate(getLocalTimeZone()))
  return `${start} - ${end}`
})

const updateStartValue = (startDate: DateRange['start']) => {
  if (model.value) {
    model.value = { ...model.value, start: startDate }
  } else {
    model.value = { start: startDate, end: undefined }
  }
}

const selectedValues = computed(() => {
  let count = 0

  if (model.value?.start) count++
  if (model.value?.end) count++

  return count
})
</script>

<template>
  <div :class="cn('grid h-8 gap-2', $attrs.class ?? '')">
    <Popover>
      <PopoverTrigger as-child>
        <Button
          id="date"
          variant="outline"
          size="sm"
          :class="
            cn(
              'w-full justify-start border-dashed text-left font-normal',
              !model && 'text-muted-foreground',
            )
          "
        >
          <CalendarIcon class="h-4 w-4 shrink-0 ltr:mr-2 rtl:ml-2" />
          <span class="font-medium">{{ formattedDateRange }}</span>
          <template v-if="selectedValues && !isDesktop">
            <Separator orientation="vertical" class="mx-2 h-4" />
            <Badge
              variant="secondary"
              class="rounded-sm px-1 font-normal lg:hidden"
            >
              {{ selectedValues }}
            </Badge>
            <div class="hidden space-x-1 lg:flex">
              <Badge variant="secondary" class="rounded-sm px-1 font-normal">
                {{ selectedValues }}
              </Badge>
            </div>
          </template>
        </Button>
      </PopoverTrigger>
      <PopoverContent class="w-auto p-0" align="end">
        <RangeCalendar
          v-model="model"
          locale="ar-US"
          :weekStartsOn="6"
          initial-focus
          @update:start-value="updateStartValue"
        />
      </PopoverContent>
    </Popover>
  </div>
</template>
