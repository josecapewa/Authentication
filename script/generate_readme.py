import os
import openai  # ou outra API que preferir
import requests

# Configurações da API da IA (exemplo OpenAI)
openai.api_key = os.getenv("OPENAI_API_KEY")

def list_files_in_repository(repo_path='.'):
    """Lista todos os arquivos no repositório."""
    files = []
    for root, dirs, filenames in os.walk(repo_path):
        for filename in filenames:
            if filename.endswith('.php') or filename.endswith('.md'):  # Escolha extensões relevantes
                files.append(os.path.join(root, filename))
    return files

def read_file_content(file_path):
    """Lê o conteúdo de um arquivo."""
    with open(file_path, 'r', encoding='utf-8') as file:
        return file.read()

def generate_readme_content(files):
    """Gera o conteúdo do README.md usando uma IA."""
    files_content = "\n\n".join([f"### {file}\n\n{read_file_content(file)}" for file in files])

    # Use a API de IA para gerar o conteúdo do README
    response = openai.Completion.create(
        model="text-davinci-003",  # Exemplo usando o GPT-3 da OpenAI
        prompt=f"Baseado nos seguintes arquivos de código, crie um README.md detalhado:\n\n{files_content}",
        max_tokens=1500
    )

    return response['choices'][0]['text']

def save_readme(content, filename='README.md'):
    """Salva o conteúdo gerado no arquivo README.md."""
    with open(filename, 'w', encoding='utf-8') as file:
        file.write(content)

if __name__ == "__main__":
    # Lista todos os arquivos no repositório
    repo_files = list_files_in_repository()

    # Gera o conteúdo do README usando IA
    readme_content = generate_readme_content(repo_files)

    # Salva o novo README.md
    save_readme(readme_content)
