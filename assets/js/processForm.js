export default async function processForm(form, url) {
   
        const formData = new FormData(form);
        const formobj = Object.fromEntries(formData.entries())
        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formobj)
            })
            if (!response.ok) {
                throw new Error('error fetching data');
            
            }
            const data = await response.json();
           return data;
        } catch (error) {
            console.error(error)
            return null;
           
        }
   
}