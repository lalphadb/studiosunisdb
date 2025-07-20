<template>
  <div class="bg-gray-800 p-4 rounded-xl shadow">
    <h3 class="text-lg font-semibold mb-2 text-white">{{ title }}</h3>
    <canvas ref="canvas" height="200"></canvas>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import { Chart, registerables } from 'chart.js'

// Enregistrer tous les composants Chart.js
Chart.register(...registerables)

const props = defineProps<{
  title: string
  data: { labels: string[]; values: number[] }
  type?: string
}>()

const canvas = ref<HTMLCanvasElement | null>(null)
let chart: Chart | null = null

onMounted(() => renderChart())
watch(() => props.data, () => renderChart(), { deep: true })

function renderChart() {
  if (!canvas.value) return

  if (chart) {
    chart.destroy()
  }

  chart = new Chart(canvas.value, {
    type: (props.type as any) || 'line',
    data: {
      labels: props.data.labels,
      datasets: [{
        label: props.title,
        data: props.data.values,
        backgroundColor: 'rgba(59, 130, 246, 0.3)',
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: {
            color: 'white'
          }
        }
      },
      scales: {
        x: {
          ticks: { color: 'white' },
          grid: { color: 'rgba(255,255,255,0.1)' }
        },
        y: { 
          beginAtZero: true,
          ticks: { color: 'white' },
          grid: { color: 'rgba(255,255,255,0.1)' }
        }
      }
    }
  })
}
</script>
