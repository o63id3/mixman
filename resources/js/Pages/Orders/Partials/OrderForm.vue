<script setup lang="ts">
import Combobox from '@/Components/combobox/Combobox.vue'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Textarea } from '@/Components/ui/textarea'
import { Button } from '@/Components/ui/button'

import { PlusCircle } from 'lucide-vue-next'

import CardItemForm from './CardItemForm.vue'

import { orderStatues } from '@/types/enums'
import { Card, User } from '@/types'

const emit = defineEmits(['select'])

defineProps<{
  users: Array<User>
  cards?: Array<Card>
  selected?: number
}>()
</script>

<template>
  <FormField name="user_id">
    <FormItem>
      <FormLabel>المستفيد</FormLabel>
      <Combobox
        :options="
          users.map((seller) => ({
            value: seller.id,
            label: seller.name,
          }))
        "
        choose-title="اختر مستفيد..."
        search-placeholder="ابحث عن نقطة بيع"
        :selected="selected"
        @select="(selected: any) => emit('select', selected.value)"
      />
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="status">
    <FormItem>
      <FormLabel>الحالة</FormLabel>
      <FormControl>
        <Select v-bind="componentField">
          <SelectTrigger>
            <SelectValue placeholder="اختر حالة الطلب" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem
                v-for="status in orderStatues"
                :key="status.value"
                :value="status.value"
              >
                <div class="flex items-center justify-end gap-1">
                  {{ status.label }}
                  <component :is="status.icon" class="w-4" />
                </div>
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-if="cards" v-slot="{ componentField }" name="cards">
    <FormItem class="col-span-full">
      <FormLabel>الكروت</FormLabel>
      <FormControl class="col-span-full">
        <CardItemForm
          v-for="(card, index) in componentField.modelValue"
          :key="index"
          class="mb-2 flex gap-1 border-b pb-3"
          :cards="cards"
          :can-delete="componentField.modelValue.length > 1"
          @delete="componentField.modelValue.splice(index, 1)"
          v-model:card="card.card_id"
          v-model:packages="card.number_of_packages"
          v-model:cardsPerPackage="card.number_of_cards_per_package"
        />
        <Button
          type="button"
          variant="outline"
          @click="
            componentField.modelValue.push({
              card_id: '1',
              number_of_packages: 1,
              number_of_cards_per_package: 120,
            })
          "
        >
          <PlusCircle class="w-5" />
        </Button>
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="notes">
    <FormItem class="col-span-full">
      <FormLabel>ملاحظات</FormLabel>
      <FormControl>
        <Textarea v-bind="componentField" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
