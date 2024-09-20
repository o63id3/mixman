<script setup lang="ts">
import { Button } from '@/Components/ui/button'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
  SelectLabel,
} from '@/Components/ui/select'
import { Trash2Icon } from 'lucide-vue-next'
import { Input } from '@/Components/ui/input'
import Label from '@/Components/ui/label/Label.vue'
import { Card } from '@/types'

const card = defineModel<string>('card')
const packages = defineModel<number>('packages')
const cardsPerPackage = defineModel<number>('cardsPerPackage')

const emit = defineEmits(['delete'])

defineProps<{
  cards: Array<Card>
  canDelete: boolean
}>()
</script>

<template>
  <div>
    <div class="grid flex-1 grid-cols-5 gap-1">
      <div class="col-span-3 space-y-0.5">
        <Label class="text-xs"> الفئة </Label>
        <Select v-model="card">
          <SelectTrigger class="overflow-hidden">
            <SelectValue placeholder="اختر الفئة" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectLabel>الفئات</SelectLabel>
              <SelectItem
                v-for="card in cards"
                :key="card.id"
                :value="String(card.id)"
              >
                {{ card.name }}
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </div>
      <div class="space-y-0.5">
        <Label class="text-xs"> عدد الرزم </Label>
        <Input v-model="packages" type="number" />
      </div>
      <div class="space-y-0.5">
        <Label class="text-xs"> عدد الكروت </Label>
        <Input v-model="cardsPerPackage" type="number" />
      </div>
    </div>
    <Button
      type="button"
      variant="destructive"
      class="place-self-end"
      @click="emit('delete')"
      :disabled="!canDelete"
    >
      <Trash2Icon class="h-5 w-5" />
    </Button>
  </div>
</template>
