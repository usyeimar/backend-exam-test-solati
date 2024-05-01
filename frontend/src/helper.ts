const formatearFecha = (fecha: Date) => {
  const newFecha = new Date(fecha)
  const dia = fecha.getDate().toString().padStart(2, '0')
  const mes = (fecha.getMonth() + 1).toString().padStart(2, '0') // ¡Recuerda que los meses están indexados desde 0!
  const anio = fecha.getFullYear()
  return `${dia}-${mes}-${anio}`
}

export { formatearFecha }