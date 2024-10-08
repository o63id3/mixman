<script setup lang="ts">
import { router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
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
import { useSubmit } from '@/Composables/submit'

const props = defineProps<{
  order: Order
  cards: Array<Card>
}>()

const emit = defineEmits(['success'])

const addItemsSchema = toTypedSchema(
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

const { handleSubmit, setErrors, resetForm } = useForm({
  validationSchema: addItemsSchema,
  initialValues: {
    cards: [
      {
        card_id: '1',
        number_of_packages: 1,
        number_of_cards_per_package: 120,
      },
    ],
  },
})

const { submit, loading } = useSubmit(
  route('order-items.store', props.order.id),
  {
    method: 'post',
    onSuccess: () => {
      toast({ title: 'تم إضافة الرزم' })
      resetForm()
      emit('success')
    },
    onError: (errors) => setErrors(errors),
  },
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <form class="space-y-6" @submit="onSubmit">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <FormField v-slot="{ componentField }" name="cards">
        <FormItem class="col-span-full">
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
    </div>
    <Button type="submit" :disabled="loading" :loading="loading">
      إضافة
    </Button>
  </form>
</template>
