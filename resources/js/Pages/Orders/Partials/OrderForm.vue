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
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { Card, Order, User } from '@/types'
import { orderStatues } from '@/types/enums'
import CardItemForm from './CardItemForm.vue'
import { PlusCircle } from 'lucide-vue-next'
import Button from '@/Components/ui/button/Button.vue'
import Input from '@/Components/ui/input/Input.vue'

const emit = defineEmits(['select'])

defineProps<{
  order?: Order
  sellers?: Array<User>
  cards?: Array<Card>
  selected?: number
  hiddenCards?: boolean
  disabled?: boolean
}>()
</script>

<template>
  <FormField name="seller_id">
    <FormItem>
      <FormLabel>نقطة البيع</FormLabel>
      <Combobox
        v-if="sellers"
        :options="
          sellers.map((seller) => ({
            value: seller.id,
            label: seller.name,
          }))
        "
        choose-title="اختر بائع..."
        search-placeholder="ابحث عن نقطة بيع"
        :selected="selected"
        @select="(selected: any) => emit('select', selected.value)"
      />
      <Input v-else :model-value="order?.seller.name" disabled />
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="status">
    <FormItem>
      <FormLabel>الحالة</FormLabel>
      <FormControl>
        <Select v-bind="componentField" :disabled="disabled">
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
  <FormField
    v-if="!hiddenCards && cards"
    v-slot="{ componentField }"
    name="cards"
  >
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
        <Textarea v-bind="componentField" :disabled="disabled" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
