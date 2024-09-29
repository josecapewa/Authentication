import os
import openai
import chardet

# Função para detectar a codificação do arquivo e lê-lo corretamente
def read_file_content(file_path):
    """Detecta a codificação do arquivo e lê seu conteúdo."""
    with open(file_path, 'rb') as file:  # Abrir em modo binário
        raw_data = file.read()
        result = chardet.detect(raw_data)
        encoding = result['encoding']

    # Ler o arquivo com a codificação detectada, ignorando erros
    with open(file_path, 'r', encoding=encoding, errors='ignore') as file:
        return file.read()

# Função para listar todos os arquivos do repositório, filtrando por extensões relevantes
def list_files_in_repository(repo_path='.'):
    """Lista todos os arquivos relevantes no repositório."""
    files = []
    for root, dirs, filenames in os.walk(repo_path):
        for filename in filenames:
            # Ignorar arquivos que não são de interesse (por exemplo, binários)
            if not filename.endswith(('.php', '.md', '.txt', '.js', '.css')):  # Adicione extensões que deseja processar
                continue
            files.append(os.path.join(root, filename))
    return files

# Função para gerar o conteúdo do README.md usando a nova API OpenAI
def generate_readme_content(files):
    """Gera o conteúdo do README.md usando a OpenAI."""
    files_content = "\n\n".join([f"### {file}\n\n{read_file_content(file)}" for file in files])
    
    # Chamada à nova API OpenAI para gerar o conteúdo do README.md
    prompt = f"Leia os seguintes arquivos e gere uma documentação apropriada para um README.md:\n\n{files_content}"
    
    response = openai.chat_completions.create(
        model="gpt-3.5-turbo",  # ou "gpt-4" se estiver disponível para você
        messages=[
            {"role": "system", "content": "Você é um assistente que cria documentações."},
            {"role": "user", "content": prompt}
        ],
        max_tokens=2048  # Ajuste conforme necessário
    )

    return response['choices'][0]['message']['content'].strip()

# Função principal
if __name__ == "__main__":
    # Certifique-se de definir a variável de ambiente OPENAI_API_KEY no GitHub Secrets
    openai.api_key = os.getenv("OPENAI_API_KEY")

    # Listar os arquivos no repositório
    repo_files = list_files_in_repository()

    # Gerar o conteúdo do README.md
    readme_content = generate_readme_content(repo_files)

    # Escrever o conteúdo gerado no README.md
    with open("README.md", "w", encoding="utf-8") as readme_file:
        readme_file.write(readme_content)
