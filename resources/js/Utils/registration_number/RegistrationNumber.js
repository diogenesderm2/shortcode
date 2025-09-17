export function generateRegistrationNumber() {
   const timestamp = Date.now().toString()
  return timestamp.slice(-10)
}
