<script setup lang="ts">
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { Button } from '@/Components/ui/button'
import {
  FormControl,
  FormField,
  FormItem,
  FormMessage,
} from '@/Components/ui/form'
import { toast } from '@/Components/ui/toast'
import { Card, Order } from '@/types'
import CardItemForm from './CardItemForm.vue'
import { PlusCircleIcon } from 'lucide-vue-next'

defineProps<{
  order: Order
  cards: Array<Card>
}>()

const emit = defineEmits(['success'])

const formSchema = toTypedSchema(
  z.object({
    cards: z.array(
      z.object({
        card_id: z.string({ message: 'حقل الفئة مطلوب' }),
        number_of_packages: z.number({ message: 'حقل الرزم مطلوب' }).min(1),
        number_of_cards_per_package: z.number({ message: 'حقل الكروت مطلوب' }),
      }),
    ),
  }),
)

const initialValues = {
  cards: [
    {
      card_id: '1',
      number_of_packages: 1,
      number_of_cards_per_package: 120,
    },
  ],
}
</script>

<template>
  <CreateFormLayout
    :form-schema="formSchema"
    :initial-values="initialValues"
    :route="route('order-cards.store', order.id)"
    @success="
      () => {
        toast({ title: 'تم إضافة الرزم' })
        emit('success')
      }
    "
    btn-title="إضافة"
  >
    <FormField v-slot="{ componentField }" name="cards">
      <FormItem class="col-span-full">
        <FormControl>
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
          <div>
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
              <PlusCircleIcon class="w-5" />
            </Button>
          </div>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>
  </CreateFormLayout>
</template>
